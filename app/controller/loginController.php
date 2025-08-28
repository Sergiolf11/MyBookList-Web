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

// Prepared statement evitando get_result (compatibilidad sin mysqlnd)
$stmt = $db->prepare("SELECT Id_User, Username, Password, Rol, Email, FotoPerfil FROM usuario WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

$id = $uname = $hash = $rol = $email = $foto = null;
$stmt->bind_result($id, $uname, $hash, $rol, $email, $foto);
$rowFound = $stmt->fetch();

if ($rowFound && password_verify($password, $hash)) {
    session_regenerate_id(true);
    $_SESSION['userid'] = $id;
    $_SESSION['user'] = $uname;
    $_SESSION['rol'] = $rol;
    $_SESSION['email'] = $email;
    $_SESSION['fotoPerfil'] = $foto;

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
