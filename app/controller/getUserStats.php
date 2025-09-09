<?php
session_start();
header('Content-Type: application/json');

try {
    // Incluir la conexión a la base de datos
    include '../../config/conexion.php';
    
    $userId = $_SESSION['id_usuario'];
    
    // Obtener género favorito (más leído)
    $sqlGenre = "
        SELECT g.Genero, COUNT(ul.Id_Libro) as total_libros
        FROM usuario_libro ul
        JOIN libro l ON ul.Id_Libro = l.Id_Libro
        JOIN genero g ON l.Id_Genero = g.Id_Genero
        WHERE ul.Id_Usuario = ? AND ul.Estado = 'Completed'
        GROUP BY g.Id_Genero, g.Genero
        ORDER BY total_libros DESC
        LIMIT 1
    ";
    
    $stmt = $db->prepare($sqlGenre);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $resultGenre = $stmt->get_result();
    
    $favoriteGenre = "No hay datos";
    if ($rowGenre = $resultGenre->fetch_assoc()) {
        $favoriteGenre = $rowGenre['Genero'] . " (" . $rowGenre['total_libros'] . " libros)";
    }
    
    // Obtener autor más leído
    $sqlAuthor = "
        SELECT l.Autor, COUNT(ul.Id_Libro) as total_libros
        FROM usuario_libro ul
        JOIN libro l ON ul.Id_Libro = l.Id_Libro
        WHERE ul.Id_Usuario = ? AND ul.Estado = 'Completed'
        GROUP BY l.Autor
        ORDER BY total_libros DESC
        LIMIT 1
    ";
    
    $stmt = $db->prepare($sqlAuthor);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $resultAuthor = $stmt->get_result();
    
    $topAuthor = "No hay datos";
    if ($rowAuthor = $resultAuthor->fetch_assoc()) {
        $topAuthor = $rowAuthor['Autor'] . " (" . $rowAuthor['total_libros'] . " libros)";
    }
    
    // Devolver respuesta JSON
    echo json_encode([
        'success' => true,
        'favoriteGenre' => $favoriteGenre,
        'topAuthor' => $topAuthor
    ]);
    
} catch (Exception $e) {
    // En caso de error
    echo json_encode([
        'success' => false,
        'error' => 'Error al cargar estadísticas: ' . $e->getMessage()
    ]);
}
?>
