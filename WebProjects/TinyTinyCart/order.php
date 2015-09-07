<?php
require('init.php');

//Paypal is notifying
if(isset($_GET["token"]) && isset($_GET["PayerID"]) && isset($_SESSION['pp_cart']) && isset($_SESSION['pp_amount']))
{
    //Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, (getConfigKey('paypal_testmode'))?'https://api-3t.sandbox.paypal.com/nvp':'https://api-3t.paypal.com/nvp');
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    //Mixing voodoo magic soup and send it to paypal...
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'USER='.urlencode(getConfigKey('paypal_user')).'&PWD='.urlencode(getConfigKey('paypal_password')).'&SIGNATURE='.urlencode(getConfigKey('paypal_signature')).'&VERSION=76.0&METHOD=DoExpressCheckoutPayment&CURRENCYCODE=EUR&PAYMENTREQUEST_0_CURRENCYCODE=EUR&PAYMENTREQUEST_0_PAYMENTACTION=Sale&PAYERID='.urlencode(getVar('PayerID')).'&PAYMENTREQUEST_0_AMT='.$_SESSION['pp_amount'].'&TOKEN='.urlencode(getVar('token')));

    $httpResponse = curl_exec($ch);
    if(!$httpResponse)
        render('error', array('error'=>'Request error'));

    // Extract the response details.
    $httpResponseAr = explode("&", $httpResponse);
    $httpParsedResponseAr = array();
    foreach ($httpResponseAr as $i => $value)
    {
        $tmpAr = explode("=", $value);
        if(sizeof($tmpAr) > 1)
            $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
    }
    if(!(sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
        render('error', array('error'=>'Invalid response'));

    //Check if everything went ok..
    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
    {
        //Insert order and items in DB, delete old cart and items...
        $configQuery = $dbh->prepare("SELECT cartitems.qty as qty, products.id as id FROM products JOIN cartitems ON cartitems.product_id = products.id WHERE cartitems.cart_id = :cart_id");
        $configQuery->execute(array(':cart_id'=>$_SESSION['pp_cart']));

        //Add order
        $addItem = $dbh->prepare("INSERT INTO orders (user_id, total, status) VALUES (:user_id, :total, :status)");
        $addItem->execute(array(':user_id'=>$_SESSION['id'], ':total'=>$_SESSION['pp_amount'], ':status'=>$httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]));
        $order_id = $dbh->lastInsertId('id');
        while ($product = $configQuery->fetch())
        {
            //Add item to order
            $addItem = $dbh->prepare("INSERT INTO orderitems (order_id, product_id, qty) VALUES (:order_id, :product_id, :qty)");
            $addItem->execute(array(':order_id'=>$order_id, ':product_id'=>$product['id'], ':qty'=>$product['qty']));
        }

        //Delete old cart and old cart items, create now empty cart.
        $sth = $dbh->prepare("DELETE FROM carts WHERE id = :id");
        $sth->execute(array(':id' => $_SESSION['pp_cart']));
        $sth = $dbh->prepare("DELETE FROM cartitems WHERE cart_id = :id");
        $sth->execute(array(':id' => $_SESSION['pp_cart']));
        $sth = $dbh->prepare("INSERT INTO carts (token_id) VALUES (:token)");
        $sth->execute(array(':token'=>$_SESSION['token']));

        if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
            render('order', array('status'=>1, 'transaction'=>$httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]));
        elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
            render('order', array('status'=>2, 'transaction'=>$httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]));
        else
            render('order', array('status'=>0));
    }
    elseif (TC_DEBUG)
    {
            echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
            echo '<pre>';
            print_r($httpParsedResponseAr);
            echo '</pre>';
    }
    else
        render('error', array('error'=>'Paypal error'));
}
else
    render('error', array('error'=>'Paypal error'));

?>