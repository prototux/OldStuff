<?php session_start(); ?>
<div class="block_content container detail">
	<section class="products">
		<form action="cart" method="post">
		<div class="row">
			<div class="span12">
				<h4 class="title header"><strong>Your</strong> cart</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Remove</th>
							<th>Image</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Unit Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$total = 0;
							foreach($vars as $product)
							{
									$price = ($product['is_onsale'])?$product['sale_price']:$product['price'];
									echo '<tr>';
									echo '<td><input type="checkbox" name="delete-'.$product['id'].'"></td>';
									echo '<td><a href="product/'.$product['id'].'"><img class="thumb" alt="" src="img/products/'.$product['id'].'.png"></a></td>';
									echo '<td>'.$product['name'].'</td>';
									echo '<td><input type="text" name="qty-'.$product['id'].'" value="'.$product['qty'].'" class="input-mini"></td>';
									echo '<td>$'.$price.'</td>';
									echo '<td>$'.$price*$product['qty'].'</td>';
									echo '</tr>';
									$total += $price*$product['qty'];
							}
						?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><strong>$<?php echo $total; ?></strong></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<p class="center">
					<input type="hidden" name="update" value="true" />
					<button class="btn btn-inverse" type="submit">Update</button>
					<?php
						echo ($_SESSION['id'])?'<button class="btn btn-inverse btn-submit" type="submit" id="btn-submit">Checkout</button>':'You must be member to checkout';
						if ($_SESSION['id'])
							echo '<form method="post" action="payment" id="submitme"><input type="hidden" name="id" value="'.$vars[0]['cart_id'].'" /></form>';
					?>
				</p>
			</div>
		</div>
	</form>
	</section>
</div>