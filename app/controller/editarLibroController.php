<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $idlibro = $_SESSION['idlibro'];
    $titulo = $_POST['titulo'];  
    $autor = $_POST['autor'];  
    $saga = $_POST['saga'];  
    $genero = $_POST['genero'];
    $numSaga = $_POST['numSaga'];  
    $sinopsis = $_POST['sinopsis'];  
    $portada = $_POST['portada'];  
    
    $sql = "UPDATE libro SET Titulo='$titulo',Saga='$saga',Autor='$autor',Genero='$genero',Num_Saga='$numSaga',Sinopsis='$sinopsis',Portada='$portada' WHERE Id_Libro = ".$idlibro."";
                
    $result = $db->query($sql);  
    if($result){  
        echo "<script>window.location='../view/libro.php?idlibro=".$idlibro."'</script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($db);  
    }   
?>