<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../public/img/ico1.ico">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="../../public/css/libro.css">
</head>
<body>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<div class="v-card">
	<!-- Header -->
	<header>
		<ul class="list-inline">
			<a class='btn btn-info text-white' href="home.php" >Home</a>
            <a class='btn btn-info text-white' href="anadir.php" >New</a>
            <?php
            // Obtener el rol desde la variable de entorno
            $rol = $_SESSION['rol'];
            // Si el rol es 1
                // Conectar a la base de datos
                include '../../config/conexion.php';
                // Buscar un libro aleatorio con algún campo NULL
                $query = "SELECT Id_Libro FROM libro WHERE Editorial IS NULL OR Portada IS NULL OR Num_Saga IS NULL OR Sinopsis IS NULL LIMIT 1";
                $result = $db->query($query);
                // Verificar si se encontró un libro
                if ($row = $result->fetch_assoc()) {
                    $idLibro = $row['Id_Libro'];

                    // Mostrar el enlace solo si hay un libro con campos NULL
                    echo "<li class='nav-item'>";
                    echo "<a class='nav-link' href='editarLibro.php?idlibro=".$idLibro."'>Correccion</a>";
                    echo "</li>";
                }
            ?>
			<?php
                require_once('../controller/libroController.php');
                setToList();
            ?>
		</ul>
	</header>
	<!-- tab Content -->
	<div class="tab-content">
		<!-- Tab Pane - Home -->
		<div class="tab-pane fade active in" id="home">
            <?php
                require_once('../controller/libroController.php');
                getlibro();
            ?>			
		</div>
	</div>
</div>                                        

<script type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
const rateLabels = document.querySelectorAll('.rate label');
const rateRadios = document.querySelectorAll('.rate input[type="radio"]');

rateLabels.forEach((label, index) => {
    label.addEventListener('mouseover', () => {
        // Activa las estrellas hasta la que está siendo "hovered"
        for (let i = index; i >= 0; i--) {
            rateLabels[i].style.backgroundImage = "url('../../public/img/estrella-activa.png')";
        }
    });

    label.addEventListener('mouseout', () => {
        // Devuelve las estrellas a su estado original
        const checkedRadio = document.querySelector('.rate input[type="radio"]:checked');
        const checkedIndex = checkedRadio ? Array.from(rateRadios).indexOf(checkedRadio) : -1;

        for (let i = 0; i < rateLabels.length; i++) {
            if (i <= checkedIndex) {
                rateLabels[i].style.backgroundImage = "url('../../public/img/estrella-activa.png')";
            } else {
                rateLabels[i].style.backgroundImage = "url('../../public/img/estrella-desactivada.png')";
            }
        }
    });
});

</script>
<script>
// Obtener la URL actual
const urlParams = new URLSearchParams(window.location.search);

// Extraer el id_libro de la URL
const id_libro = urlParams.get('idlibro');
function saveRating(rating) {
    // Hacer una solicitud AJAX para guardar la calificación
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../controller/estrellaPost.php", true); // Cambia a la ruta correcta
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Aquí puedes manejar la respuesta si lo necesitas
            document.getElementById("userRating").textContent = rating; // Actualiza la calificación mostrada
        }
    };
    xhr.send("rating=" + rating+ "&id_libro=" + id_libro); // Envía la calificación al servidor
}
</script>
<script>
    const stars = document.querySelectorAll('.rate input');
    const userRatingSpan = document.getElementById('userRating');

    stars.forEach(star => {
        star.addEventListener('change', () => {
            userRatingSpan.textContent = star.value; // Actualiza la calificación mostrada
        });
    });
</script>
</body>
</html>