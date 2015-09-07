<div class="block_content container detail">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title header"><strong>Your</strong> order</h4>
				<table class="table table-striped">
					<thead>
						<tr>
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
									echo '<td><a href="product/'.$product['id'].'"><img class="thumb" alt="" src="img/products/'.$product['id'].'.png"></a></td>';
									echo '<td>'.$product['name'].'</td>';
									echo '<td>'.$product['qty'].'</td>';
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
							<td><strong>$<?php echo $total; ?></strong></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>
</div>