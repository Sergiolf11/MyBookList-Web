<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../public/img/ico1.ico">
    <link rel="stylesheet" href="../../public/css/libro.css">
    <!-- Theme Switcher CSS -->
    <link rel="stylesheet" href="../../public/css/themes.css">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body data-theme="dark">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<div class="v-card">
    <header>
		<ul class="list-inline">
			<li class="active"><a href="home.php" >Home</a></li>
		</ul>
	</header> <br>
    <?php
        require_once('../controller/libroController.php');
        printButtoms();
    ?>
</div>

<!-- Theme Switcher JS -->
<script src="../../public/js/theme-switcher.js"></script>

<script>
// Sincronizar el tema cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-theme', savedTheme);
});
</script>
</body>
</html>