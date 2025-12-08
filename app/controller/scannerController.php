<?php 
session_start();
include '../../config/conexion.php'; 
echo "<!-- Inicio del script -->\n";

if (isset($_GET['ISBN'])) {
    echo "<!-- ISBN recibido: " . htmlspecialchars($_GET['ISBN']) . " -->\n";
    $isbn = $_GET['ISBN'];
    $_SESSION['isbn_pendiente'] = $isbn;
    sleep(5);
    // Consulta en la base de datos si el libro ya existe por ISBN
    $sqlisbn = "select * from libro where ISBN = '$isbn'";  
    $resultisbn = $db->query($sqlisbn);
    echo "<!-- Consulta SQL ejecutada: " . htmlspecialchars($sqlisbn) . " -->\n";
    echo "<!-- Número de filas encontradas: " . $resultisbn->num_rows . " -->\n";

    if ($resultisbn->num_rows > 0) {
        $rowisbn = $resultisbn->fetch_assoc();
        $idlibro = $rowisbn['Id_Libro'];
        // Redirigir a libro.php con el idlibro
        header("Location: ../view/libro.php?idlibro=$idlibro");
        exit;
    }
    
    echo "<!-- Iniciando búsqueda en APIs externas -->\n";
    
    // Función para hacer peticiones HTTP con manejo de errores mejorado
    function fetchBookData($url) {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // Timeout de 5 segundos
                'ignore_errors' => true, // Para obtener la respuesta incluso con códigos de error HTTP
                'follow_location' => true, // Seguir redirecciones automáticamente
                'max_redirects' => 5 // Límite de redirecciones
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        // Obtener información del error
        if ($response === false) {
            $error = error_get_last();
            echo "<script>console.error('Error en la petición a $url: ", json_encode($error), "');</script>";
            return null;
        }
        
        // Verificar el código de estado HTTP
        global $http_response_header;
        $status_line = $http_response_header[0] ?? '';
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        $status = $match[1] ?? '000';
        
        // Obtener la URL final después de las redirecciones
        $final_url = $url;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (stripos($header, 'location:') === 0) {
                    $final_url = trim(substr($header, 9));
                    break;
                }
            }
        }
        
        if ($status !== '200') {
            echo "<script>console.warn('Respuesta HTTP $status de $url', 'Redirigido a: $final_url');</script>";
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<script>console.error('Error decodificando JSON de $url: ", json_last_error_msg(), "');</script>";
            return null;
        }
        
        return $data;
    }

    // 1. Open Library API
    $openlibrary_url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
    echo "<!-- Consultando Open Library API: " . htmlspecialchars($openlibrary_url) . " -->\n";
    $openlibrary_data = fetchBookData($openlibrary_url);
    $book_key = "ISBN:$isbn";
    echo "<!-- Datos recibidos de Open Library: " . (empty($openlibrary_data) ? 'No data' : 'Data received') . " -->\n";
    
    // 2. Google Books API
    $google_books_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
    echo "<!-- Consultando Google Books API: " . htmlspecialchars($google_books_url) . " -->\n";
    $google_data = fetchBookData($google_books_url);
    echo "<!-- Datos recibidos de Google Books: " . (empty($google_data) ? 'No data' : 'Data received') . " -->\n";
    
    // 3. Open Library Works API (información más detallada)
    $ol_works_url = "https://openlibrary.org/isbn/$isbn.json";
    echo "<!-- Consultando Open Library Works API: " . htmlspecialchars($ol_works_url) . " -->\n";
    $ol_works_data = fetchBookData($ol_works_url);
    echo "<!-- Datos recibidos de Open Library Works: " . (empty($ol_works_data) ? 'No data' : 'Data received') . " -->\n";
    
    // Mostrar resultados en consola
    echo "<!-- Mostrando resultados en consola JavaScript -->\n";
    echo "<script>
        console.log('=== RESULTADOS DE LAS APIS ===');
        console.log('1. Open Library API:');
        console.log(" . json_encode(isset($openlibrary_data[$book_key]) ? $openlibrary_data[$book_key] : 'No data') . ");
        
        console.log('2. Google Books API:');
        console.log(" . json_encode($google_data ?: 'No data') . ");
        
        console.log('3. Open Library Works API:');
        console.log(" . json_encode($ol_works_data ?: 'No data') . ");
    </script>";

    // Continuar con la lógica original usando Open Library
    echo "<!-- Validando datos de Open Library -->\n";
    if (!$openlibrary_data || !isset($openlibrary_data[$book_key])) {
        echo "<!-- No se encontraron datos válidos en Open Library -->\n";
        echo "<p>No se encontró información para el ISBN: " . htmlspecialchars($isbn) . "</p>";
        exit;
    }

    $book = $openlibrary_data[$book_key];

    // Extraer los datos necesarios
    echo "<!-- Extrayendo datos del libro -->\n";
    $title = $book['title'] ?? '';
    // Si el título contiene una '/', tomamos solo la primera parte (generalmente en español)
    $title = explode(' / ', $title)[0];
    if ($title === strtoupper($title)) {  
        $title = ucfirst(strtolower($title));  
    }
    $title=strtolower($title);
    // Extraer los autores de Open Library
    $authors = isset($book['authors']) ? $book['authors'] : [];

    // Verificar si hay autores y tomar solo el primero
    $first_author = '';
    if (!empty($authors)) {
        // Tomar solo el primer autor
        $first_author = $authors[0]['name'] ?? 'Autor no disponible';
    }
    //$first_author=strtolower($first_author);
    $cover = $book['cover']['large'] ?? 'https://via.placeholder.com/128x193?text=Sin+Imagen';
    $publishers = isset($book['publishers']) ? implode(", ", array_column($book['publishers'], 'name')) : 'Editorial desconocida';
    $publishers = explode(', ', $publishers)[0];
    if($publishers == 'Debolsillo, DEBOLSILLO'){
        $publishers=' DEBOLS!LLO';
    }
    $excerpt = $book['excerpt']['value'] ?? 'No hay resumen disponible';


    // Consulta en la base de datos si el libro ya existe
    $sql = "select * from libro where LOWER(Titulo) = LOWER('" . $db->real_escape_string($title) . "') AND LOWER(Autor) = LOWER('" . $db->real_escape_string($first_author) . "')";
    echo "<!-- Buscando libro en la base de datos: " . htmlspecialchars($sql) . " -->\n";
    $result = $db->query($sql);
    echo "<!-- Resultados de búsqueda: " . $result->num_rows . " coincidencias encontradas -->\n"; 

    echo "<!-- Verificando resultados de búsqueda -->\n";
    if ($resultisbn->num_rows > 0) {
        echo "<!-- Libro encontrado por ISBN -->\n";
        $rowisbn = $resultisbn->fetch_assoc();
        $idlibro = $rowisbn['Id_Libro'];
        echo "<!-- Redirigiendo a libro.php?idlibro=$idlibro -->\n";
        header("Location: ../view/libro.php?idlibro=$idlibro");
        exit;
    } else if($result->num_rows > 0) {
        echo "<!-- Libro encontrado por título y autor -->\n";
        $row = $result->fetch_assoc();
        $idlibro = $row['Id_Libro'];
        $sqlupdate = "UPDATE libro SET ISBN='$isbn' WHERE Id_Libro = ".$idlibro."";
        $resultupdate = $db->query($sqlupdate);  
        if($resultupdate){  
            header("Location: ../view/libro.php?idlibro=$idlibro");
            exit;
        }  
        else{  
            echo "Error: ".$sql."<br>".$mysql_error($db);  
        }   
        // Redirigir a libro.php con el idlibro
    } else {
        echo "<!-- Libro no encontrado, redirigiendo a formulario de escaneo -->\n";
        // Redirigir a scanner.php pasándole el ISBN
        $redirect_url = "../view/scanner.php?ISBN=" . urlencode($isbn);
        echo "<!-- URL de redirección: " . htmlspecialchars($redirect_url) . " -->\n";
        header("Location: $redirect_url");
        exit;
    }

    $conn->close();
} else {
    echo "<!-- No se recibió ningún parámetro ISBN -->\n";
    echo "<p>No se recibió ningún ISBN</p>\n";
    echo "<pre>\$_GET = " . htmlspecialchars(print_r($_GET, true)) . "</pre>\n";
}
?>