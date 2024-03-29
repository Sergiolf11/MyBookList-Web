<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../../public/img/ico1.ico">
    <link rel="stylesheet" href="../../public/css/editarPerfil.css">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
	<script> 
        $(function(){
            $("#header").load("header.php"); 
        });
    </script>
</head>
<body class="bg-light">
	<div id="header"></div>
	<br>
	<div class="container">
		<div class="main-body">
			<div class="row">
				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
							<div class="d-flex flex-column align-items-center text-center">
							<?php
								require_once('../controller/userController.php');  
								getFirstBox();
							?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="card">
						<div class="card-body">
							<form action="../controller/editarPerfilController.php" method="POST" class="login-form">
								<?php
									require_once('../controller/userController.php');  
									form1();
								?>
							</form>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<form action="../controller/cambiarPasswordController.php" method="POST" class="login-form">
								<?php
									require_once('../controller/userController.php');  
									form2();
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
</script>
</body>
<script>
		$(document).ready(function() {
			document.getElementById("erroneo").style.visibility = "hidden";
			if(localStorage.getItem("incorrectPass")==null){
				localStorage.setItem("incorrectPass","false");
			}else if(localStorage.getItem("incorrectPass")=="true"){
				document.getElementById("erroneo").style.visibility = "visible";
			}else{
				document.getElementById("erroneo").style.visibility = "hidden";
			}
        });
	</script>
</html>