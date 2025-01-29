<?php 
session_start();
include '../../config/conexion.php'; 
if (isset($_GET['ISBN'])) {
    $isbn = $_GET['ISBN'];
    $api_url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";

    $response = file_get_contents($api_url);
    if ($response === false) {
        echo "<p>Error al conectar con Open Library</p>";
        exit;
    }

    $data = json_decode($response, true);
    $book_key = "ISBN:$isbn";

    if (!isset($data[$book_key])) {
        echo "<p>No se encontró información para el ISBN: $isbn</p>";
        exit;
    }

    $book = $data[$book_key];

    // Extraer los datos necesarios
    $title = $book['title'] ?? 'Título no disponible';
    $authors = isset($book['authors']) ? implode(", ", array_column($book['authors'], 'name')) : 'Autor no disponible';
    $cover = $book['cover']['large'] ?? 'https://via.placeholder.com/128x193?text=Sin+Imagen';
    $publishers = isset($book['publishers']) ? implode(", ", array_column($book['publishers'], 'name')) : 'Editorial desconocida';
    $excerpt = $book['excerpt']['value'] ?? 'No hay resumen disponible';

    // Consulta en la base de datos si el libro ya existe
    $sql = "select * from libro where LOWER(Titulo) = LOWER('$title') AND LOWER(Autor) = LOWER('$authors')";  
    $result = $db->query($sql);  

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idlibro = $row['Id_Libro'];
        // Redirigir a libro.php con el idlibro
        header("Location: http://mybooklist.rf.gd/app/view/libro.php?idlibro=$idlibro");
        exit;
    } else {
        // Redirigir a scanner.php pasándole el ISBN
        header("Location: ../view/scanner.php?ISBN=$isbn");
        exit;
    }

    $conn->close();
} else {
    echo "<p>No se recibió ningún ISBN</p>";
}
?>