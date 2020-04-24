<div class="block_content container detail">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title header"><strong>Reset</strong> your password</h4>
				<div class="span">
					<?php if (!$vars)
					{
						echo '<form action="user/reset" method="post" class="form">';
						echo '<fieldset>';
						echo '<label>Email</label>';
						echo '<div class="div_text">';
						echo '<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="email" type="text" value="" class="span6"></div>';
						echo '</div>';
						echo '<button class="btn btn-inverse" type="submit">Reset</button>';
						echo '</fieldset>';
						echo '</form>';
					}
					else
						echo '<div class="span">A new password was sent to your email</div>';
					?>
				</div>
			</div>
		</div>
	</section>
</div>
</div>