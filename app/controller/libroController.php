<?php
session_start();


if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}

function getLibro(){
// Include the database config file 
include '../../config/conexion.php'; 
    $idlibro = $_GET['idlibro'];
    $userid = $_SESSION['userid'];
    $sql = "select * from libro l join genero g on l.Genero=g.Id_Genero where Id_Libro = '$idlibro'";  
    $result = $db->query($sql);  

    // Consulta para obtener el estado del usuario con respecto al libro
    $sqlestado = "select * from usuario_libro ul join usuario u on ul.Id_User=ul.Id_User where u.Id_User = '$userid' and Id_Libro = '$idlibro'";  
    $resultestado = $db->query($sqlestado); 
    $rowestado = mysqli_fetch_array($resultestado, MYSQLI_ASSOC);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["Num_Saga"] == 0){
                $SagaNum="<br>";
            }else{
                $SagaNum="<p><a id='saga' href='../view/home.php?search=".$row["Saga"]."'>".$row["Saga"]."</a> ".$row["Num_Saga"]."</p>";
            }

            // Manejo de autores
            $autores = $row["Autor"];
            $autores_links = [];

            if (strpos($autores, ',') !== false) {
                // Si hay comas, separa los autores
                $autores_array = explode(',', $autores);
                foreach ($autores_array as $autor) {
                    $autor = trim($autor); // Elimina espacios en blanco
                    $autores_links[] = "<a href='../view/home.php?search=" . urlencode($autor) . "'><small>" . htmlspecialchars($autor) . "</small></a>";
                }
            } else {
                // Si no hay comas, solo un autor
                $autor = trim($autores);
                $autores_links[] = "<a href='../view/home.php?search=" . urlencode($autor) . "'><small>" . htmlspecialchars($autor) . "</small></a>";
            }

            $autores_html = implode(', ', $autores_links); // Une los enlaces con comas

            echo "
            <br>
            <div class='vcard-img'>
			<img src='".$row["Portada"]."' alt='' class='img-rounded img-responsive'></br>";
            if($rowestado["Estado"] == "Completed"){
                // Aquí se asume que 'Estrellas' está en la tabla 'usuario_libro'
                $estrellasUsuario = isset($rowestado['Estrellas']) ? $rowestado['Estrellas'] : 0;
                echo "<div class='container'>";
                    echo "<div class='rate'>";
                        for ($i = 1; $i <= 5; $i++) {
                            // Marcar la estrella como checked si la calificación es mayor o igual que la estrella actual
                            $checked = ($estrellasUsuario >= $i) ? "checked='checked'" : "";
                            $starImage = ($estrellasUsuario >= $i) ? "../../public/img/estrella-activa.png" : "../../public/img/estrella-desactivada.png"; 

                            echo "<input type='radio' id='star$i' name='rating' value='$i' $checked onclick='saveRating($i)'>"; // Llama a saveRating() al hacer clic
                            echo "<label for='star$i' style='background-image: url($starImage);'></label>"; // Establece la imagen de fondo en la etiqueta
                        }
                    echo "</div>";
                    
                    echo "<div class='overall-rating'>";
                        echo "(Your Rating: <span id='userRating'>" . htmlspecialchars($estrellasUsuario) . "</span>)"; // Se muestra la valoración del usuario
                    echo "</div>";
                echo "</div>";

            }
            echo "
			</div>";
            // SECCION DERECHA
            echo "
			<div class='vcard-content'>

				<h4>" . htmlspecialchars($row["Titulo"]) . " " . $autores_html . "</h4>
                ".$SagaNum."
                <p> <b>Genero:</b> <a style='color: inherit; ' href='../view/home.php?genero=".$row["Id_Genero"]."'><small>".$row["Genero"]."</small></a> &emsp;&emsp;&emsp;";
                if ($row["Editorial"] != NULL){
                    echo "<b>Editorial:</b> <a style='color: inherit; ' href='../view/home.php?editorial=".$row["Editorial"]."'><small>".$row["Editorial"]."</small></a>";
                }
                echo "
                </p>
                <hr>
				<p id='sinopsis'>".$row["Sinopsis"]."</p>
				<hr>
			</div>
			<!-- Clearfix -->
			<div class='clearfix'></div>";
        }
    } else {
        echo "Ese libro no existe";
    }
}

function setToList(){
    // Include the database config file 
    include '../../config/conexion.php'; 

    $idlibro = $_GET['idlibro'];
    $userid = $_SESSION['userid'];
    $rol = $_SESSION['rol'];
    $sql = "select * from usuario_libro ul join usuario u on ul.Id_User=ul.Id_User where u.Id_User = '$userid' and Id_Libro = '$idlibro'";  
    $result = $db->query($sql); 
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    echo "
    <a class='btn btn-info text-white' href='setList.php?idlibro=".$idlibro."' ><i class='fa fa-table'></i> ".$row["Estado"]."</a>";
    //Que no muestre los botones editar y borrar si no es admin
    if($rol == "1"){
        echo "
        <a class='btn btn-warning text-white' href='editarLibro.php?idlibro=".$idlibro."'><i class='fa fa-edit'></i></a>
        <a class='btn btn-danger text-white' href='#' onclick='confirmarEliminacion()'><i class='fa fa-trash'></i></a>
        <script>
        function confirmarEliminacion() {
            // Muestra un cuadro de confirmación
            var confirmacion = confirm('¿Estás seguro de que deseas eliminar?');

            // Si el usuario confirma, redirige a la página de eliminación
            if (confirmacion) {
                window.location.href = '../controller/eliminarController.php?idLibro=".$idlibro."'; 
            }
            // Si el usuario cancela, no hace nada
        }
        </script>";
    }
    echo "
    &emsp;";
    if($row["Estado"]=="Completed" && $row["Fecha_Inicio"] !== NULL && $row["Fecha_Inicio"] !== "0000-00-00" && $row["Fecha_Fin"] !== NULL && $row["Fecha_Fin"] !== "0000-00-00"){
        $date1 = new DateTime($row["Fecha_Inicio"]);
        $date2 = new DateTime($row["Fecha_Fin"] );

        // Calcular la diferencia en días
        $diferencia = $date1->diff($date2);

        // Obtener el número de días de la diferencia y asegurarse de que sea al menos 1
        $numDias = max(1, $diferencia->days);

        echo "Terminado en: ".$numDias." dias";
    }else  if($row["Estado"]=="Completed" && $row["Fecha_Fin"] !== NULL && $row["Fecha_Fin"] !== "0000-00-00" ){
        $date1 = new DateTime($row["Fecha_Fin"]);

        echo "Terminaste este libro el dia ".$row["Fecha_Fin"]."";
    }else  if($row["Estado"]=="Reading" && $row["Fecha_Inicio"] !== NULL && $row["Fecha_Inicio"] !== "0000-00-00" ){
        $date1 = new DateTime($row["Fecha_Inicio"]);
        $date2 = new DateTime();

        // Calcular la diferencia en días
        $diferencia = $date1->diff($date2);

        // Obtener el número de días de la diferencia y asegurarse de que sea al menos 1
        $numDias = max(1, $diferencia->days);

        echo "Llevas: ".$numDias." dias leyendo este libro";
    }
    //ToDo boton borrar
}


function printButtoms(){
    $idlibro = $_GET['idlibro'];
    echo "
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=1' ><button class='bg-success' style='width: 100% ;height:20%;border: none;'>Reading</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=2' ><button class='bg-primary' style='width: 100% ;height:20%;border: none;'>Completed</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=3' ><button class='bg-warning' style='width: 100% ;height:20%;border: none;'>On Hold</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=4' ><button class='bg-danger text-white' style='width: 100% ;height:20%;border: none;'>Dropped</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=5' ><button class='bg-secondary text-white' style='width: 100% ;height:20%;border: none;'>Plan to Read</button></a>";
}

function select(){
    
    include '../../config/conexion.php'; 
    $sql = "select * from genero order by Genero ASC";
    $result = $db->query($sql);  
    
    echo "<select name='genero' class='form-control'>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['Id_Genero'];
        $genero = $row['Genero']; 
        echo '<option value="'.htmlspecialchars($id).'">'.htmlspecialchars($genero).'</option>';
    }
    echo "</select>";

}

function editLibro(){
    // Include the database config file 
    include '../../config/conexion.php'; 

    $idlibro = $_GET['idlibro'];
    $_SESSION['idlibro'] = $idlibro;
    $sql = "select * from libro where Id_Libro = '$idlibro'";  
    $result = $db->query($sql);  
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
    echo "
        <div class='form-group'>
            <input type='text' name='titulo' placeholder='Titulo' class='form-control' required value='".$row['Titulo']."'>
        </div>

        <div class='form-group'>
            <input type='text' name='autor' class='form-control' placeholder='Autor' required value='".$row['Autor']."'>
        </div>

        <div class='form-group'>
            <input type='text' name='saga' class='form-control' placeholder='Saga' required value='".$row['Saga']."'>
        </div>

        <div class='form-group'>
            <input type='text' name='numSaga' class='form-control' placeholder='Numero del libro dentro de la saga' required value='".$row['Num_Saga']."'>
        </div>

        <div class='form-group'>
        ";
        $sqlGeneros  = "select * from genero order by Genero ASC";
        $resultGeneros  = $db->query($sqlGeneros);  
        $generos = [];
        while ($rowGenero = $resultGeneros->fetch_assoc()) {
            $generos[] = $rowGenero;
        }
    
        echo "<select name='genero' class='form-control'>";
        foreach ($generos as $genero) {
            $selected = ($genero['Id_Genero'] == $row['Genero']) ? 'selected' : '';
            echo "<option value='{$genero['Id_Genero']}' $selected>{$genero['Genero']}</option>";
        }
        echo "
            </select>
        </div>
        
        <div class='form-group'>
            <input  type='text' name='portada' class='form-control' placeholder='URL de imagen de la portada' required value='".$row['Portada']."'>
        </div>

        <div class='form-group'>
            <input  type='text' name='editorial' class='form-control' placeholder='Editorial' value='".$row['Editorial']."'>
        </div>

        <div class='form-group'>
            <input  type='text' name='idioma' class='form-control' placeholder='Idioma' required value='".$row['Idioma']."'>
        </div>

        <div class='form-group'>
            <textarea style='height: 200px;' type='text' id='sinopsis' name='sinopsis' class='form-control' placeholder='Sinopsis' required >".$row['Sinopsis']."</textarea>
        </div>

        <div class='form-group'>
            <button type='submit' class='btn form-control btn-primary rounded submit px-3'>Guardar</button>
        </div>
    ";
}

function añadirLibro(){
    // Include the database config file 
    include '../../config/conexion.php'; 

    // Obtener el ISBN de la URL
    $isbn = $_GET['ISBN'];

    // Realizar la consulta a Open Library usando el ISBN
    $api_url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
    $response = file_get_contents($api_url);
    
    if ($response === false) {
        echo "<p>Error al conectar con Open Library</p>";
        exit;
    }

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);
    $book_key = "ISBN:$isbn";

    if (!isset($data[$book_key])) {
        echo "<p>No se encontró información para el ISBN: $isbn</p>";
        exit;
    }
    // Extraer los datos del libro desde la respuesta
    $book = $data[$book_key];
    $title = $book['title'] ?? '';
    $cover = $book['cover']['large'] ?? '';
    $publishers = isset($book['publishers']) ? implode(", ", array_column($book['publishers'], 'name')) : '';
    $excerpt = $book['excerpt']['value'] ?? '';

    // Extraer el idioma, si existe, de la respuesta de Open Library
    $language_code = isset($book['languages']) ? $book['languages'][0] : '';

    // Asignar el valor adecuado para el idioma
    if ($language_code == 'en') {
        $language = 'EN'; // Inglés
    } elseif ($language_code == 'spa' || $language_code == 'es') {
        $language = 'ES'; // Español
    } else {
        $language = ''; // Otros idiomas
    }

    // Extraer los autores de Open Library
    $authors = isset($book['authors']) ? $book['authors'] : [];

    // Verificar si hay autores y tomar solo el primero
    $first_author = '';
    if (!empty($authors)) {
        // Tomar solo el primer autor
        $first_author = $authors[0]['name'] ?? 'Autor no disponible';
    }

    // Mostrar el formulario con los datos obtenidos de Open Library y la base de datos (si existe)
    echo "
        <div class='form-group'>
            <input type='text' name='titulo' placeholder='Titulo' class='form-control' required value='" . ($title ?: $row['Titulo']) . "'>
        </div>

        <div class='form-group'>
            <input type='text' name='autor' class='form-control' placeholder='Autor' required value='" . ($first_author ?: $row['Autor']) . "'>
        </div>

        <div class='form-group'>
            <input type='text' name='saga' class='form-control' placeholder='Saga' required value='" . ($title) . "'>
        </div>

        <div class='form-group'>
            <input type='text' name='numSaga' class='form-control' placeholder='Numero del libro dentro de la saga' required value='0'>
        </div>

        <div class='form-group'>
    ";

    // Obtener géneros desde la base de datos
    $sqlGeneros  = "SELECT * FROM genero ORDER BY Genero ASC";
    $resultGeneros  = $db->query($sqlGeneros);  
    $generos = [];
    while ($rowGenero = $resultGeneros->fetch_assoc()) {
        $generos[] = $rowGenero;
    }

    echo "<select name='genero' class='form-control'>";
    foreach ($generos as $genero) {
        $selected = ($genero['Id_Genero'] == $row['Genero']) ? 'selected' : '';
        echo "<option value='{$genero['Id_Genero']}' $selected>{$genero['Genero']}</option>";
    }
    echo "</select></div>

        <div class='form-group'>
            <input type='text' name='portada' class='form-control' placeholder='URL de imagen de la portada' required value='" . ($cover ?: $row['Portada']) . "'>
        </div>

        <div class='form-group'>
            <input type='text' name='editorial' class='form-control' placeholder='Editorial' value='" . ($publishers ?: $row['Editorial']) . "'>
        </div>

        <div class='form-group'>
            <input type='text' name='idioma' class='form-control' placeholder='Idioma' required value='" . ($language ?: 'ES') . "'>
        </div>

        <div class='form-group'>
            <textarea style='height: 200px;' type='text' id='sinopsis' name='sinopsis' class='form-control' placeholder='Sinopsis' required >" . ($excerpt ?: $row['Sinopsis']) . "</textarea>
        </div>

        <div class='form-group'>
            <button type='submit' class='btn form-control btn-primary rounded submit px-3'>Guardar</button>
        </div>
    ";
}


?>