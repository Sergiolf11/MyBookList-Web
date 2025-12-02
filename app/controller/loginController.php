<?php 
// Iniciar sesión al principio del script
session_start();

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

include '../../config/conexion.php'; 

$username = $_POST['username'] ?? '';  
$password = $_POST['password'] ?? '';  

$username = stripcslashes($username);  
$password = stripcslashes($password);  

// Usar consultas preparadas para prevenir inyección SQL
$sql = "SELECT * FROM usuario WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Verificar si se encontró el usuario y la contraseña es correcta
if($row && password_verify($password, $row["Password"])){
    // Establecer variables de sesión
    $_SESSION['userid'] = $row["Id_User"];
    $_SESSION['user'] = $row["Username"];
    $_SESSION['rol'] = $row["Rol"];
    $_SESSION['email'] = $row["Email"];
    $_SESSION['fotoPerfil'] = $row["FotoPerfil"];
    
    // Redirección con control de acceso desde localStorage
    echo "<script>localStorage.setItem('denegado','false');</script>";
    
    // Verificar si hay un ISBN pendiente después del login
    if (isset($_SESSION['isbn_pendiente']) && !empty($_SESSION['isbn_pendiente'])) {
        $isbn = htmlspecialchars($_SESSION['isbn_pendiente'], ENT_QUOTES, 'UTF-8');
        unset($_SESSION['isbn_pendiente']); // Limpia para que no redirija siempre
        echo "<script>window.location.href = '../controller/scannerController.php?ISBN=" . urlencode($isbn) . "';</script>";
    } else {
        echo "<script>window.location='../view/home.php'</script>";  
    }
} else {  
    echo "<script>localStorage.setItem('denegado','true');</script>";
    echo "<script>window.location='../view/login.php'</script>"; 
}
?>
