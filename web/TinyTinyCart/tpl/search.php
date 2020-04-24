<div class="block_content container detail main-content bg-colleft">
	<section class="products">
		<div class="row">
			<div class="span9">
				<h4 class="title header">Search results</h4>
					<ul class="thumbnails listing-products">
						<?php
						if ($vars)
							foreach($vars as $product)
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
						else
							echo '<div class="span">Sorry, no results found</div>';
						?>
					</ul>
				<hr/>
			</div>
			<div class="span3 col">
				<img alt="" src="img/ad.png" />
			</div>
		</div>
	</section>
</div>