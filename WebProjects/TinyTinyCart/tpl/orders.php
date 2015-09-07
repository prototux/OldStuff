<div class="block_content container detail">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title header"><strong>Your</strong> orders</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>date</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($vars as $product)
							{
								echo '<tr>';
								echo '<td><a href="orders/'.$product['id'].'"> Order #'.$product['id'].'</a></td>';
								echo '<td>'.$product['date'].'</td>';
								echo '<td>$'.$product['total'].'</td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>