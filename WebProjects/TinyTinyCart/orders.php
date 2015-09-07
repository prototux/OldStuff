<?php
	require('init.php');
	if (getVar('id'))
	{
		$orderQuery = $dbh->prepare("SELECT products.id as id, products.name as name, orderitems.qty as qty, products.price as price, products.sale_price as sale_price, products.is_onsale as is_onsale FROM products JOIN orderitems ON orderitems.product_id = products.id JOIN orders ON orders.id = orderitems.order_id WHERE orderitems.order_id = :id AND orders.user_id = :user_id");
		$orderQuery->execute(array(':id'=>getVar('id'), ':user_id' => $_SESSION['id']));
	 	$orderInfos = $orderQuery->fetchAll();
	 	if (!$orderQuery->rowCount())
	 		render('error', array('error'=>'This is not your order!'));
	 	render('order-details', $orderInfos);
	}
	else
	{
		$ordersQuery = $dbh->prepare("SELECT * FROM orders WHERE user_id = :id");
		$ordersQuery->execute(array(':id'=>$_SESSION['id']));
	 	$ordersInfos = $ordersQuery->fetchAll();
	 	render('orders', $ordersInfos);
	}
?>