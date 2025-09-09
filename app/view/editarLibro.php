<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../../public/img/ico1.ico">
	<link rel="stylesheet" href="../../public/css/libro.css">
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Theme Switcher CSS -->
	<link rel="stylesheet" href="../../public/css/themes.css">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body data-theme="dark">
                        
<div class="v-card">
	<!-- Header -->
	<header>
		<ul class="list-inline">
			<li class="active"><a href="home.php" >Home</a></li>
		</ul>
		<h3>Edita el libro</h3>
	</header>
	<!-- tab Content -->
	<div class="tab-content">
		<!-- Tab Pane - Home -->
		<div class="tab-pane fade active in" id="home">
            <form action="../controller/editarLibroController.php" method="POST">
                <?php
                    require_once('../controller/libroController.php');  
                    editLibro();
                ?>
            </form>
		</div>
	</div>
</div>

<!-- Theme Switcher JS -->
<script src="../../public/js/theme-switcher.js"></script>

<!-- Sidebar JS -->
<script src="../../public/js/sidebar.js"></script>

<script>
// Sincronizar el tema cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-theme', savedTheme);
});
</script>
</body>
</html>