<?php 
session_start();
include '../../config/conexion.php'; 
if (isset($_GET['ISBN'])) {
    $isbn = $_GET['ISBN'];
    $_SESSION['isbn_pendiente'] = $isbn;
    sleep(5);
    // Consulta en la base de datos si el libro ya existe por ISBN
    $sqlisbn = "select * from libro where ISBN = '$isbn'";  
    $resultisbn = $db->query($sqlisbn);

    if ($resultisbn->num_rows > 0) {
        $rowisbn = $resultisbn->fetch_assoc();
        $idlibro = $rowisbn['Id_Libro'];
        // Redirigir a libro.php con el idlibro
        header("Location: ../view/libro.php?idlibro=$idlibro");
        exit;
    }
    
    // Función para hacer peticiones HTTP con manejo de errores mejorado
    function fetchBookData($url) {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // Timeout de 5 segundos
                'ignore_errors' => true // Para obtener la respuesta incluso con códigos de error HTTP
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
        if (isset($http_response_header[0])) {
            $status_line = $http_response_header[0];
            preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
            $status = $match[1] ?? '000';
            
            if ($status !== '200') {
                echo "<script>console.warn('Respuesta HTTP $status de $url: ", json_encode($status_line), "');</script>";
            }
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
    $openlibrary_data = fetchBookData($openlibrary_url);
    $book_key = "ISBN:$isbn";
    
    // 2. Google Books API
    $google_books_url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
    $google_data = fetchBookData($google_books_url);
    
    // 3. Open Library Works API (información más detallada)
    $ol_works_url = "https://openlibrary.org/isbn/$isbn.json";
    $ol_works_data = fetchBookData($ol_works_url);
    
    // Mostrar resultados en consola
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
    if (!$openlibrary_data || !isset($openlibrary_data[$book_key])) {
        echo "<p>No se encontró información para el ISBN: $isbn</p>";
        exit;
    }

    $book = $openlibrary_data[$book_key];

    // Extraer los datos necesarios
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
    $sql = "select * from libro where LOWER(Titulo) = LOWER('$title') AND LOWER(Autor) = LOWER('$first_author')";  
    $result = $db->query($sql); 

    if ($resultisbn->num_rows > 0) {
        $rowisbn = $resultisbn->fetch_assoc();
        $idlibro = $rowisbn['Id_Libro'];
        // Redirigir a libro.php con el idlibro
        header("Location: ../view/libro.php?idlibro=$idlibro");
        exit;
    }else if($result->num_rows > 0){
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
        // Redirigir a scanner.php pasándole el ISBN
        //$title = str_replace(' ', '+', $title);
        //echo "<script>window.location='../view/home.php?search=".$title."'</script>";   
        header("Location: ../view/scanner.php?ISBN=$isbn");
        exit;
    }

    $conn->close();
} else {
    echo "<p>No se recibió ningún ISBN</p>";
}
?>