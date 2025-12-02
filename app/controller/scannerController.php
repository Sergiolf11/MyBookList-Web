<?php 
session_start();
require_once '../../config/conexion.php';
require_once '../lib/BookSearch.php';

if (!isset($_GET['ISBN'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "<p>No se recibió ningún ISBN</p>";
    exit;
}

$isbn = trim($_GET['ISBN']);
$_SESSION['isbn_pendiente'] = $isbn;

// Crear instancia del buscador de libros
$bookSearch = new BookSearch($db);

// Verificar si el libro ya existe en la base de datos
$stmt = $db->prepare("SELECT Id_Libro FROM libro WHERE ISBN = ? LIMIT 1");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Libro encontrado, redirigir a su página
    $row = $result->fetch_assoc();
    header("Location: ../view/libro.php?idlibro=" . $row['Id_Libro']);
    exit;
}

// Buscar información del libro en las APIs
try {
    $bookInfo = $bookSearch->buscarLibro($isbn);
    
    if ($bookInfo) {
        // Verificar si el libro ya existe por título y autor (por si el ISBN es diferente)
        $titulo = $bookInfo['titulo'];
        $autor = $bookInfo['autor'];
        
        $stmt = $db->prepare("SELECT Id_Libro FROM libro WHERE LOWER(Titulo) = LOWER(?) AND LOWER(Autor) = LOWER(?) LIMIT 1");
        $stmt->bind_param("ss", $titulo, $autor);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Actualizar el ISBN del libro existente
            $row = $result->fetch_assoc();
            $updateStmt = $db->prepare("UPDATE libro SET ISBN = ? WHERE Id_Libro = ?");
            $updateStmt->bind_param("si", $isbn, $row['Id_Libro']);
            $updateStmt->execute();
            
            header("Location: ../view/libro.php?idlibro=" . $row['Id_Libro']);
            exit;
        }
        
        // Almacenar la información del libro en la sesión para mostrarla en el formulario
        $_SESSION['libro_temporal'] = $bookInfo;
        header("Location: ../view/scanner.php?ISBN=$isbn");
        exit;
    } else {
        // No se encontró el libro en ninguna API
        $_SESSION['error'] = "No se pudo encontrar información para el ISBN: $isbn. Por favor, introduce los datos manualmente.";
        header("Location: ../view/scanner.php?ISBN=$isbn");
        exit;
    }
} catch (Exception $e) {
    error_log("Error en scannerController: " . $e->getMessage());
    $_SESSION['error'] = "Ocurrió un error al buscar el libro. Por favor, inténtalo de nuevo más tarde.";
    header("Location: ../view/scanner.php?ISBN=$isbn");
    exit;
}
?>