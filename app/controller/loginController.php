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

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Prepared statement para evitar SQLi
$stmt = $db->prepare("SELECT * FROM usuario WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && password_verify($password, $row["Password"])) {
    session_regenerate_id(true);
    $_SESSION['userid'] = $row["Id_User"];
    $_SESSION['user'] = $row["Username"];
    $_SESSION['rol'] = $row["Rol"];
    $_SESSION['email'] = $row["Email"];
    $_SESSION['fotoPerfil'] = $row["FotoPerfil"];

    if (isset($_SESSION['isbn_pendiente'])) {
        $isbn = $_SESSION['isbn_pendiente'];
        unset($_SESSION['isbn_pendiente']);
        header("Location: ../controller/scannerController.php?ISBN=" . urlencode($isbn));
        exit;
    }
    header("Location: ../view/home.php");
    exit;
} else {
    header("Location: ../view/login.php?denegado=1");
    exit;
}
?>
