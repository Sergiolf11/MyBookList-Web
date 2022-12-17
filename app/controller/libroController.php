<?php
session_start();


if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}
function getLibro(){
// Include the database config file 
include '../../config/conexion.php'; 
    $idlibro = $_GET['idlibro'];
    $sql = "select * from libro where Id_Libro = '$idlibro'";  
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
    if($rol == "1"){
        echo "
        <a class='btn btn-warning text-white' href='editarLibro.php?idlibro=".$idlibro."'><i class='fa fa-edit'></i></a>
        <a class='btn btn-danger text-white' href='notFound.php'><i class='fa fa-trash'></i></a>";
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
?>