<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}
function getLibro(){
    $con = new mysqli('localhost', 'root', '', 'mybooklist');    
    $idlibro = $_GET['idlibro'];
    $sql = "select * from libro where Id_Libro = '$idlibro'";  
    $result = mysqli_query($con, $sql);  

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "
            <br>
            <div class='vcard-img'>
			<img src='".$row["Portada"]."' alt='' class='img-rounded img-responsive'>
			</div>
			<div class='vcard-content'>
				<h4>".$row["Titulo"]." <small>".$row["Autor"]."</small></h4>
                <p>".$row["Saga"]." ".$row["Num_Saga"]."</p>
				<p>".$row["Sinopsis"]."</p>
				<hr>
			</div>
			<!-- Clearfix -->
			<div class='clearfix'></div>";
        }
    } else {
        echo "Ese libro no existe";
    }
    $con->close();
}

function setToList(){
    //session_start();
    $idlibro = $_GET['idlibro'];
    echo "<li class='active'><a href='setList.php?idlibro=".$idlibro."' >Añadir a lista</a></li>";
}

function printButtoms(){
    $idlibro = $_GET['idlibro'];
    echo "
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=1' ><button class='bg-success' style='width: 100% ;height:20%;border: none;'>Reading</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=2' ><button class='bg-primary' style='width: 100% ;height:20%;border: none;'>Completed</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=3' ><button class='bg-warning' style='width: 100% ;height:20%;border: none;'>On Hold</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=4' ><button class='bg-danger text-white' style='width: 100% ;height:20%;border: none;'>Dropped</button></a><br><br>
    <a href='../controller/setListController.php?idLibro=".$idlibro."&status=5' ><button class='bg-secondary text-white' style='width: 100% ;height:20%;border: none;'></a>Plan to Read</button>";
}
?>