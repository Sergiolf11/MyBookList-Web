<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../../public/img/ico1.ico">
    <link rel="stylesheet" href="../../public/css/libro.css">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
                        
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<div class="v-card">
	<!-- Header -->
	<header>
		<ul class="list-inline">
			<li class="active"><a href="home.php" >Home</a></li>
		</ul>
	</header>
	<!-- tab Content -->
	<div class="tab-content">
		<!-- Tab Pane - Home -->
		<div class="tab-pane fade active in" id="home">
            <form action="../controller/añadirController.php" method="POST">
                <?php
                    require_once('../controller/libroController.php');  
                    añadirLibro();
                ?>
            </form>
		</div>
	</div>
</div>                                        
</body>
</html>