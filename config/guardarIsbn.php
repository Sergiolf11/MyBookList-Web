<?php
session_start();

// Include the database config file 
include './conexion.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Permitir peticiones desde el mismo dominio o cualquier dominio (*)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejar las solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Si no llegan datos, termina la ejecución
if (!isset($_POST['id']) || !isset($_POST['isbn'])) {
    die("Error: No se recibieron los datos correctamente.");
}

// Verificar si se recibieron los datos
if (isset($_POST['id']) && isset($_POST['isbn'])) {
    $id = $_POST['id']; // Sanitizar el ID
    $isbn = $_POST['isbn']; // Sanitizar el ISBN

    // Insertar en la base de datos
    $sql = "UPDATE libro SET ISBN='$isbn' WHERE Id_Libro = ".$id."";
    $result = $db->query($sql);  
    if($result){  
        echo "ISBN guardado correctamente para el libro $id.";
    } else {
        echo "Error al guardar el ISBN.";
    }
}

?>
