<div class="row" id="top-bar">
	<div class="span7 logo">
        <a href="index"><img alt="" src="img/logo.png" /></a>
	</div>
	<div class="span5 options">
		<div class="account pull-right"><?php echo (!$_SESSION['id'])?'<a href="#loginModal" role="button" data-toggle="modal"><i class="icon-user"></i> Login</a>':'<strong>Welcome</strong> '.$_SESSION['firstname'].' '.$_SESSION['lastname'].' <a href="user/logout" style="text-transform:lowercase;">(logout)</a>'; ?> | <a href="cart"><i class="icon-shopping-cart"></i> Shopping cart</a></div>
		<?php
			if ($_SESSION['id'])
				echo '<div class="subaccount pull-right"><a href="user/edit">My Account</a> | <a href="orders"><i class="icon-list"></i> Order History</a></div>';
			if ($loginFailed)
				echo '<div class="pull-right error">Login failed</div>';
			if ($userUpdated)
				echo '<div class="pull-right confirmation">Account updated <span style="color: black;">|</span></div>';
			if ($accountCreated)
				echo '<div class="pull-right confirmation">Your account is created!</div>';
		?>
	</div>
</div>