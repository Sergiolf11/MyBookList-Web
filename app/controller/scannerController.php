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