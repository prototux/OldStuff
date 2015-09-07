<?php
	include('init.php');

	//Adding product to cart
	if (getVar('product'))
	{
		//Get cart id
		$idQuery = $dbh->prepare("SELECT carts.id as id FROM carts JOIN tokens WHERE carts.token_id = tokens.id AND tokens.token = :token");
		$idQuery->execute(array(':token'=>$_SESSION['token']));
 		$cartId = $idQuery->fetchAll()[0]['id'];

 		//Add item to cart
 		$addItem = $dbh->prepare("INSERT INTO cartitems (cart_id, product_id, qty) VALUES (:cart_id, :product_id, :qty)");
		$addItem->execute(array(':cart_id'=>$cartId, ':product_id'=>$_POST['product'], ':qty'=>$_POST['qty']));
	}

	//Updating cart...
	if (getVar('update'))
	{
		//Get cart's products
		$itemsQuery = $dbh->prepare("SELECT products.id as id FROM products JOIN cartitems ON cartitems.product_id = products.id WHERE cartitems.cart_id = :id");
		$itemsQuery->execute(array(':id'=>$_SESSION['cart_id']));
	 	$itemsInfos = $itemsQuery->fetchAll();

	 	//Prepare updates
	 	$deleteItem = $dbh->prepare("DELETE FROM cartitems WHERE product_id = :product_id AND cart_id = :cart_id LIMIT 1");
		$updateItem = $dbh->prepare("UPDATE cartitems SET qty = :qty WHERE product_id = :product_id  AND cart_id = :cart_id LIMIT 1");

		//Obviously... DO the update
	 	foreach ($itemsInfos as $product)
	 	{
	 		$updateItem->execute(array(':qty' => getVar('qty-'.$product['id']),':product_id' => $product['id'], ':cart_id' => $_SESSION['cart_id']));
	 		if (getVar('delete-'.$product['id']))
				$deleteItem->execute(array(':product_id' => $product['id'], ':cart_id' => $_SESSION['cart_id']));
	 	}
	}

	//Get cart items and render...
	$cartItemsQuery = $dbh->prepare("SELECT products.id as id, products.name as name, cartitems.qty as qty, products.price as price, products.sale_price as sale_price, products.is_onsale as is_onsale, carts.id as cart_id FROM products JOIN cartitems ON cartitems.product_id = products.id JOIN carts ON cartitems.cart_id = carts.id JOIN tokens ON carts.token_id = tokens.id WHERE tokens.token = :token");
	$cartItemsQuery->execute(array(':token'=>$_SESSION['token']));
 	$cartItems = $cartItemsQuery->fetchAll();
  	$_SESSION['cart_id'] = $cartItems[0]['cart_id'];
	render('cart', $cartItems);
?>