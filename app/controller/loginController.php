<?php 
// 🔒 Duración deseada de la sesión en segundos (7 días)
$tiempoSesion = 7 * 24 * 60 * 60; // 604800 segundos

// ⏱️ Configuración del tiempo de expiración de la cookie de sesión
session_set_cookie_params([
    'lifetime' => $tiempoSesion,
    'path' => '/',
    'domain' => '', // opcional: tu dominio, ej. 'tudominio.com'
    'secure' => false, // true si usás HTTPS
    'httponly' => true,
    'samesite' => 'Lax' // o 'Strict'
]);

// 🧹 Opcional: asegurarse de que el servidor también guarde la sesión ese tiempo
ini_set('session.gc_maxlifetime', $tiempoSesion);

session_start();

include '../../config/conexion.php'; 

$username = $_POST['username'];  
$password = $_POST['password'];  

$username = stripcslashes($username);  
$password = stripcslashes($password);  

$sql = "select * from usuario where username = '$username'";  
$result = $db->query($sql);  
$row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
$isbn = $_SESSION['isbn_pendiente'];
    echo "<script>console.log('ISBN pendiente: \"$isbn\"');</script>";


if(password_verify($password,$row["Password"])){  
    $_SESSION['userid'] = $row["Id_User"];
    $_SESSION['user'] = $row["Username"];
    $_SESSION['rol'] = $row["Rol"];
    $_SESSION['email'] = $row["Email"];
    $_SESSION['fotoPerfil'] = $row["FotoPerfil"];
    // Redirección con control de acceso desde localStorage
    echo "<script>localStorage.setItem('denegado','false');</script>";
    
    if (isset($_SESSION['isbn_pendiente'])) {
        $isbn = $_SESSION['isbn_pendiente'];
        echo "<script>console.log('ISBN pendiente: \"$isbn\"');</script>";
        unset($_SESSION['isbn_pendiente']); // Limpia para que no redirija siempre
        echo "<script>window.location.href = '../controller/scannerController.php?ISBN=$isbn';</script>";
    }else{
        echo "<script>window.location='../view/home.php'</script>";  
    }
} else {  
    echo "<script>localStorage.setItem('denegado','true');</script>";
    echo "<script>window.location='../view/login.php'</script>"; 
}
?>
