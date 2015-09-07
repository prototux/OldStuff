<div class="row">
    <div class="span9">
		<div id="slides">
			<div class="slides_container">
				<a href="#"><img src="img/2.jpg" alt="" /></a>
				<a href="#"><img src="img/1.jpg" alt="" /></a>
			</div>
		</div>
    </div>
	 <div class="span3">
		<a href="#"><img src="img/ad.png" alt="" /></a>
    </div>
</div>
<div class="row services">
	<div class="span4">
		<div class="service">
			<div>
				<h4><img src="img/feature_img_1.png" alt="" />SHIPPING <strong>48/72H</strong></h4>
				<p>Your order is in your mailbox in 48/72H or we refund the shipping!</p>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="service">
			<div>
				<h4><img src="img/feature_img_2.png" alt="" />FREE <strong>SHIPPING</strong></h4>
				<p>Free shipping on orders over <strong>$1000</strong>.</p>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="service">
			<div>
				<h4><img src="img/feature_img_3.png" alt="" />24/7 LIVE <strong>SUPPORT</strong></h4>
				<p>Call us, it's free! <?php echo $config['phone']; ?>.</p>
			</div>
		</div>
	</div>
</div>
<div class="block_content container">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title feature">
					<span class="pull-left"><strong>Featured</strong> Products</span>
				</h4>
				<div class="carousel slide product-carousel">
					<div class="carousel-inner">
						<div class="active item">
							<ul class="thumbnails listing-products">
							<?php
								foreach ($featured as $product)
								{
									$price = ($product['is_onsale'])?$product['sale_price']:$product['price'];
									echo'<li class="span3">';
									echo'<div class="product-box">';
									if ($product['is_onsale'])
										echo'<span class="sale_tag"></span>';
									echo'<a href="product/'.str_replace(' ', '-', $product['name']).'/'.$product['id'].'"><img alt="" src="img/products/'.$product['id'].'.png" /></a>';
									echo'<a href="product/'.str_replace(' ', '-', $product['name']).'/'.$product['id'].'"><h4>'.$product['name'].'</h4></a>';
									echo'<p>'.$product['minidesc'].'</p>';
									echo'<p class="price">Price: <span>$'.$price.'</span></p>';
									echo'</div>';
									echo'</li>';
								}
							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="block_content container last">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title latest">
					<span class="pull-left"><strong>Latest</strong> Products</span>
				</h4>
				<div class="carousel slide product-carousel">
					<div class="carousel-inner">
						<div class="active item">
							<ul class="thumbnails listing-products">
							<?php
								foreach ($latest as $product)
								{
									$price = ($product['is_onsale'])?$product['sale_price']:$product['price'];
									echo'<li class="span3">';
									echo'<div class="product-box">';
									if ($product['is_onsale'])
										echo'<span class="sale_tag"></span>';
									echo'<a href="product/'.str_replace(' ', '-', $product['name']).'/'.$product['id'].'"><img alt="" src="img/products/'.$product['id'].'.png" /></a>';
									echo'<a href="product/'.str_replace(' ', '-', $product['name']).'/'.$product['id'].'"><h4>'.$product['name'].'</h4></a>';
									echo'<p>'.$product['minidesc'].'</p>';
									echo'<p class="price">Price: <span>$'.$price.'</span></p>';
									echo'</div>';
									echo'</li>';
								}
							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>