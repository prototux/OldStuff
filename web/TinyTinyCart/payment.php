<?php
    require('init.php');
    if(!getVar('id'))
    	render('error', array('error'=>'No ID provided'));

    //Get amount and order id
    $configQuery = $dbh->prepare("SELECT cartitems.qty as qty, products.price as price, products.sale_price as sale_price, products.is_onsale as is_onsale, carts.id as id FROM products JOIN cartitems ON cartitems.product_id = products.id JOIN carts ON cartitems.cart_id = carts.id JOIN tokens ON carts.token_id = tokens.id WHERE tokens.token = :token");
    $configQuery->execute(array(':token'=>$_SESSION['token']));
    $productInfos = $configQuery->fetchAll();
    $amount = 0;
    $cart_id = $productInfos[0]['id'];
    foreach ($productInfos as $product)
    	$amount += (($product['is_onsale'])?$product['sale_price']:$product['price'])*$product['qty'];

    //Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, (getConfigKey('paypal_testmode'))?'https://api-3t.sandbox.paypal.com/nvp':'https://api-3t.paypal.com/nvp');
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    //Mixing voodoo magic soup and send it to paypal...
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'USER='.urlencode(getConfigKey('paypal_user')).'&PWD='.urlencode(getConfigKey('paypal_password')).'&SIGNATURE='.urlencode(getConfigKey('paypal_signature')).'&VERSION=76.0&METHOD=SetExpressCheckout&PAYMENTREQUEST_0_PAYMENTACTION=Sale&PAYMENTREQUEST_0_CURRENCYCODE=EUR&PAYMENTREQUEST_0_AMT='.$amount.'&PAYMENTREQUEST_0_ITEMAMT='.$amount.'&L_PAYMENTREQUEST_0_AMT0='.$amount.'&L_PAYMENTREQUEST_0_NAME0='.getConfigKey('title').'&RETURNURL='.urlencode('http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/order').'&CANCELURL='.urlencode('http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index'));
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

    //Goto paypal or print error message
    if(strtoupper($httpParsedResponseAr["ACK"]) == 'SUCCESS' || strtoupper($httpParsedResponseAr["ACK"]) == 'SUCCESSWITHWARNING')
    {
            $_SESSION['pp_amount'] = $amount;
            $_SESSION['pp_cart'] =  $cart_id;
    		if (getConfigKey('paypal_testmode'))
            	header('Location: '. 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"]);
            else
            	header('Location: '. 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"]);
    }
    else if (TC_DEBUG)
    {
        echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
        echo '<pre>';
        print_r($httpParsedResponseAr);
        echo '</pre>';
    }
    else
    	render('error', array('error'=>'Paypal error'));
?>