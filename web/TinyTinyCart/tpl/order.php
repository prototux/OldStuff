<div class="block_content container detail">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title header"><strong>Your</strong> order</h4>
				<?php
					switch($vars['status'])
					{
						case 0: //failed
							echo '<div class="span">Order failed<br />Transaction number: '.$vars['transaction'].'</div>';
						break;
						case 1: //success
							echo '<div class="span">Order completed<br />Transaction number: '.$vars['transaction'].'</div>';
						break;
						case 2: //pending
							echo '<div class="span">Order pending, please go to your <a href="http://paypal.com">paypal</a> account to authorize it, then, contact us.<br />Transaction number: '.$vars['transaction'].'</div>';
						break;
					}
				?>
			</div>
		</div>
	</section>
</div>
</div>