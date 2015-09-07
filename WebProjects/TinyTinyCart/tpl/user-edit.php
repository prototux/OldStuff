<div class="block_content container detail">
	<section class="products">
		<div class="row">
			<div class="span12">
				<h4 class="title header"><strong>Update</strong> your account</h4>
				<div class="span">
					<form action="user/edit" method="post" class="form">
						<fieldset>
							<label>Email</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="email" type="text" value="<?php echo $vars['email']; ?>" class="span6"></div>
							</div>
							<label>First name</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="firstname" type="text" value="<?php echo $vars['firstname']; ?>" class="span6"></div>
							</div>
							<label>Last name</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="lastname" type="text" value="<?php echo $vars['lastname']; ?>" class="span6"></div>
							</div>
							<label>Address</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-home"></i></span><input name="address" type="text" value="<?php echo $vars['address']; ?>" class="span6"></div>
							</div>
							<label>Postal code</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-home"></i></span><input name="postalcode" type="text" value="<?php echo $vars['postalcode']; ?>" class="span6"></div>
							</div>
							<label>City</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-home"></i></span><input name="city" type="text" value="<?php echo $vars['city']; ?>" class="span6"></div>
							</div>
							<label>Phone number</label>
							<div class="div_text">
								<div class="input-prepend"><span class="add-on"><i class="icon-signal"></i></span><input name="phone" type="text" value="<?php echo $vars['phone']; ?>" class="span6"></div>
							</div>
							<button class="btn btn-inverse" type="submit">Update</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
</div>