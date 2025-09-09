<?php
header('Content-Type: application/json');

try {
    // Incluir la conexión a la base de datos
    include '../../config/conexion.php';
    
    // Consulta para obtener todos los géneros
    $sql = "SELECT Id_Genero, Genero FROM genero ORDER BY Genero ASC";
    $result = $db->query($sql);
    
    $genres = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genres[] = $row;
        }
    }
    
    // Devolver respuesta JSON
    echo json_encode([
        'success' => true,
        'genres' => $genres
    ]);
    
} catch (Exception $e) {
    // En caso de error
    echo json_encode([
        'success' => false,
        'error' => 'Error al cargar géneros: ' . $e->getMessage()
    ]);
}
?>
