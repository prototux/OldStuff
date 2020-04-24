		</div>
		<footer>
			<div class="container">
				<div class="row">
					<div class="span8">
						<h4>Information</h4>
						<ul>
							<li><a href="page/About-Us/1">About Us</a></li>
							<li><a href="page/Delivery-Information/2">Delivery Information</a></li>
							<li><a href="page/Privacy-Policy/3">Privacy Policy</a></li>
							<li><a href="page/Terms-Conditions/4">Terms &amp; Conditions</a></li>
						</ul>
					</div>
					<div class="span4">
						<div class="company_info">
							<h4 class="title"><?php echo $config['title']; ?> is powered by <strong>TinyTiny</strong>cart</h4>
							<p>
								<i class="icon-home"></i> <?php echo $config['address']; ?><br />
								<i class="icon-ok-circle"></i> Tel: <?php echo $config['phone']; ?>
							</p>
						</div>
					</div>

				</div>
			</div>
		</footer>
		<div class="modal hide" id="loginModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="title" id="myModalLabel">Existing Users Login</h3>
			</div>
			<div class="modal-body">
				<form action="user/login" method="post" class="form" id="loginForm">
					<fieldset>
						<label>Username</label>
						<div class="div_text">
							<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="username" type="text" value="" class="span6"></div>
						</div>
						<label>Password</label>
						<div class="div_text">
							<div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input name="password" type="password" class="span6"></div>
						</div>
						<div class="pull-right"><br/><strong>Forgot password?</strong>&nbsp;<a href="user/reset">Click here to reset</a> - <strong>New User?</strong>&nbsp;<a href="user/signup">Click here to register</a></div>
						<button class="btn btn-inverse" data-formid="loginForm" >Login</button>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer"></div>
		</div>
		<script src="js/jquery-1.7.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.easing.min.js"></script>
		<script src="js/slides.min.jquery.js"></script>
		<script type="text/javascript">
			$(function() {
				$('.carousel-product').carousel({interval: false});
				$('#slides').slides({
					preload: true,
					play: 5000,
					pause: 2500,
					hoverPause: true,
					generatePagination: false
				});
			});
			$(document).ready(function() {
				$('#btn-submit').click(function(){
					$('#submitme').submit();
				});
			});
		</script>
    </body>
</html>