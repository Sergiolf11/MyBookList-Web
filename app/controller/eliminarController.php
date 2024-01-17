<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}

$userid = $_SESSION['userid'];
$rol = $_SESSION['rol'];

// Include the database config file 
include '../../config/conexion.php'; 
$idlibro = $_GET['idLibro'];

if($rol=="1"){
        echo "$idlibro ";
        
        $sql = "delete from libro where Id_Libro = '$idlibro'";  
        $result = $db->query($sql);  
        echo "<script>window.location='../view/home.php' </script>";     
}else if($rol=="0"){
    
        echo "Error: ".$sql."<br>".$mysql_error($db);  
     
    }else{
        echo "Lo sentimos, no hemos podido eliminar ese libro";
    }

?>