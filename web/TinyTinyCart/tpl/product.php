<div class="block_content container detail main-content bg-colleft">
	<section class="products">
		<div class="row">
			<div class="span9">
				<h4 class="title header"><?php echo $product['name']; ?></h4>
				<div class="row">
					<div class="span4">
						<a href="img/products/<?php echo $product['id']; ?>.png" class="thumbnail" data-fancybox-group="group1" title="Description 1"><img alt="No image" src="img/products/<?php echo $product['id']; ?>.png"></a>
					</div>
					<div class="span5">
						<address>
							<strong>Availability:</strong> <span><?php echo (!$product['stock'])?'Out Of Stock':$product['stock'].' Available'; ?></span><br>
						</address>
						<h4><strong>Price: $<?php echo($product['is_onsale'])?$product['sale_price']:$product['price']; ?></strong></h4>
					</div>
					<div class="span5">
						<form class="form-inline" method="post" action="cart">
							<label>Qty:</label>
							<input type="text" name="qty" class="span1" value="1">
							<input type="hidden" name="product" value="<?php echo $product['id']; ?>" />
							<button class="btn btn-inverse" type="submit">Add to cart</button>
						</form>
					</div>
					<div class="span5">
						<ul class="social">
							<li>
								<div class="fb-like" data-send="false" data-layout="button_count" data-href="<?php echo $config['url']; ?>"></div>
								<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
							</li>
							<?php if ($config['twitter']) { ?>
							<li>
								<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="<?php echo $config['url']; ?>" data-text="I <3" data-via="<?php echo $config['twitter']; ?>">Tweet</a>
								<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="span9">
						<br />
						<?php echo $product['description']; ?>
					</div>
					<div class="span9">
						<br/>
						<h4 class="title">
							<span class="pull-left"><strong>Related</strong> Products</span>
						</h4>
						<div class="carousel slide">
							<div class="carousel-inner">
								<div class="active item">
									<ul class="thumbnails listing-products">
										<?php
											foreach($related as $product)
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
			</div>
			<div class="span3 col">
				<img alt="" src="img/ad.png" />
			</div>
		</div>
	</section>
</div>
</div>