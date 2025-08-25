<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}

$userid = $_SESSION['userid'];
$rol = $_SESSION['rol'];
$titulo = $_POST['titulo'];  
$autor = $_POST['autor'];  
$saga = $_POST['saga'];  
$genero = $_POST['genero'];
$numSaga = $_POST['numSaga'];  
$sinopsis = $_POST['sinopsis'];  
$portada = $_POST['portada'];  
$editorial = $_POST['editorial'];  
$idioma = $_POST['idioma'];  
$isbn = $_POST['isbn'];  

// Include the database config file 
include '../../config/conexion.php'; 

//to prevent from mysqli injection  
$titulo = stripcslashes($titulo);  
$autor = stripcslashes($autor);  
$saga = stripcslashes($saga);  
//$genero = stripcslashes($genero);  
$numSaga = stripcslashes($numSaga);  
$sinopsis = stripcslashes($sinopsis);  
$editorial = stripcslashes($editorial);  
$idioma = stripcslashes($idioma);  
$isbn = stripcslashes($isbn);  

$titulo = mysqli_real_escape_string($db, $titulo);  
$autor = mysqli_real_escape_string($db, $autor);  
$saga = mysqli_real_escape_string($db, $saga);  
//$genero = mysqli_real_escape_string($db, $genero);  
$numSaga = mysqli_real_escape_string($db, $numSaga);  
$sinopsis = mysqli_real_escape_string($db, $sinopsis);  
$editorial = mysqli_real_escape_string($db, $editorial);  
$idioma = mysqli_real_escape_string($db, $idioma);  
$isbn = mysqli_real_escape_string($db, $isbn);  

if($rol=="1"){
    $sqlregister = "INSERT INTO libro (Titulo, Autor, Saga, Num_Saga, Sinopsis, Genero, Portada, Editorial, Idioma, ISBN) VALUES ('$titulo','$autor','$saga','$numSaga','$sinopsis','$genero','$portada','$editorial','$idioma', '$isbn')";  
    $result = $db->query($sqlregister);  
    if($result){  
        $sqllibro="select * from libro where Titulo = '$titulo' and Autor='$autor' and Saga='$saga' and Num_Saga='$numSaga'";
        $result = $db->query($sqllibro); 
        $row = $result->fetch_assoc();
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado) VALUES(".$userid.",".$row["Id_Libro"].",'Plan to Read') ON DUPLICATE KEY UPDATE Estado='Plan to Read'";
        $db->query($sql);
        echo "<script>window.location='../view/libro.php?idlibro=".$row["Id_Libro"]."' </script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($db);  
    }   
}else if($rol=="0"){
    echo "Lo sentimos, no tienes permisos para agregar libros";
}
?>