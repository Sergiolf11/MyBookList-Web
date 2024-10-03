<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }

// Include the database config file 
include '../../config/conexion.php'; 

// Verificar que se reciban los datos necesarios
if (isset($_POST['rating']) && isset($_POST['id_libro'])) {
    // Sanitizar los datos recibidos
    $rating = intval($_POST['rating']); // Convertir a entero
    $id_libro = intval($_POST['id_libro']); // Convertir a entero

    // Aquí deberías tener el ID del usuario, por ejemplo desde la sesión
    // Asumiendo que tienes el ID del usuario almacenado en la sesión
    $userid = $_SESSION['userid'];

    $sql1 = "select * from usuario_libro ul join usuario u on ul.Id_User=ul.Id_User where u.Id_User = '$userid' and Id_Libro = '$id_libro'";  
    $result1 = $db->query($sql1); 
    $row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
   
    if($row['Estado']=="Completed"){
    $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado, Fecha_Inicio, Fecha_Fin, Estrellas) VALUES(".$userid.",".$row['Id_Libro'].",'Completed','".$row['Fecha_Inicio']."',".$row['Fecha_Fin'].",$rating) ON DUPLICATE KEY UPDATE Estrellas='$rating'";
    $result = $db->query($sql);
    }
      
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos no recibidos.']);
}


?>