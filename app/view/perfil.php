<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../public/img/ico1.ico">
    <link rel="stylesheet" href="../../public/css/perfil.css">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <script> 
$(function(){
  $("#header").load("header.php"); 
});
</script>
</head>
<body class="bg-light">
<div id="header"></div>
<div class="container">
    <div class="main-body">
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <?php
                        require_once('../controller/userController.php');  
                        getFirstBox();
                    ?>
                    <div class="row">
                    <div class="col-sm-12">
                      <a class="btn btn-info "  href="editarPerfil.php">Edit</a>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                <?php
                        require_once('../controller/userController.php');  
                        getSecondBox();
                    ?>
                </div>
              </div>
                <div class="col-sm-6 mb-3">
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
<script type="text/javascript">
</script>
</body>
</html>