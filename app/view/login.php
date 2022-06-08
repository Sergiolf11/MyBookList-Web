<!doctype html>
<html>
	<head>
		<title>MyBookList</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" href="../../public/img/ico1.ico">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet"/>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
		<link rel="stylesheet" href="../../public/css/style.css"/>
	</head>
	<body>
	
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Login</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap py-5">
		      	<div class="img d-flex align-items-center justify-content-center" style="background-image: url(../../public/img/bg.jpg);"></div>
		      	<h3 class="text-center mb-0">Welcome</h3>
		      	<p class="text-center">Sign in by entering the information below</p>
				
				<form action="../controller/loginController.php" method="POST" class="login-form">
		      		<div class="form-group">
		      			<div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-user"></span></div>
		      			<input type="text" name="username" class="form-control" placeholder="Username" required>
		      		</div>

		            <div class="form-group">
		            	<div class="icon d-flex align-items-center justify-content-center"><span class="fa fa-lock"></span></div>
		              <input type="password" name="password" class="form-control" placeholder="Password" required>
		            </div>
<!--
		            <div class="form-group d-md-flex">
							<div class="w-100 text-md-right">
								<a href="#">Forgot Password</a>
							</div>
		            </div>
-->
		            <div class="form-group">
		            	<button type="submit" class="btn form-control btn-primary rounded submit px-3">Get Started</button>
		            </div>
	          </form>

	          <div class="w-100 text-center mt-4 text">
	          	<p class="mb-0">Don't have an account?</p>

		          <a href="register.php">Sign Up</a>

	          </div>
	        </div>
				</div>
			</div>
		</div>
	</section>

	<script src="../../public/js/jquery.min.js"></script>
  <script src="../../public/js/popper.js"></script>
  <script src="../../public/js/bootstrap.min.js"></script>
  <script src="../../public/js/main.js"></script>

	</body>
</html>

