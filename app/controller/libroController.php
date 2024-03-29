<?php
session_start();


if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}
function getLibro(){
// Include the database config file 
include '../../config/conexion.php'; 
    $idlibro = $_GET['idlibro'];
    $sql = "select * from libro l join genero g on l.Genero=g.Id_Genero where Id_Libro = '$idlibro'";  
    $result = $db->query($sql);  

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["Num_Saga"] == 0){
                $SagaNum="<br>";
            }else{
                $SagaNum="<p><a id='saga' href='../view/home.php?search=".$row["Saga"]."'>".$row["Saga"]."</a> ".$row["Num_Saga"]."</p>";
            }
            echo "
            <br>
            <div class='vcard-img'>
			<img src='".$row["Portada"]."' alt='' class='img-rounded img-responsive'>
			</div>
			<div class='vcard-content'>
				<h4>".$row["Titulo"]." <a href='../view/home.php?search=".$row["Autor"]."'><small>".$row["Autor"]."</small></a></h4>
                ".$SagaNum."
                <p><b>Generos:</b> <small>".$row["Genero"]."</small></p>
				<p>".$row["Sinopsis"]."</p>
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
    <a class='btn btn-info text-white' href='setList.php?idlibro=".$idlibro."' ><i class='fa fa-table'></i> Añadir</a>";
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
    &emsp;&emsp;&emsp;".$row["Estado"]."";
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
    echo "$idlibro ";
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
        echo "</select>";
        echo "
        <br>
        <div class='form-group'>
            <input  type='text' name='portada' class='form-control' placeholder='URL de imagen de la portada' required value='".$row['Portada']."'>
        </div>

        <div class='form-group'>
            <textarea style='height: 200px;' type='text' id='sinopsis' name='sinopsis' class='form-control' placeholder='Sinopsis' required >".$row['Sinopsis']."</textarea>
        </div>

        <div class='form-group'>
            <button type='submit' class='btn form-control btn-primary rounded submit px-3'>Guardar</button>
        </div>
    ";
}
?>