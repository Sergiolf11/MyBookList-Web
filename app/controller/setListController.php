<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $idlibro = $_GET['idLibro'];
    $status =$_GET['status'];
    if($status=="1"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado) VALUES(".$userid.",".$idlibro.",'Reading') ON DUPLICATE KEY UPDATE Estado='Reading'";
    }
    if($status=="2"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado) VALUES(".$userid.",".$idlibro.",'Completed') ON DUPLICATE KEY UPDATE Estado='Completed'";
    }
    if($status=="3"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado) VALUES(".$userid.",".$idlibro.",'On Hold') ON DUPLICATE KEY UPDATE Estado='On Hold'";
    }
    if($status=="4"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado) VALUES(".$userid.",".$idlibro.",'Dropped') ON DUPLICATE KEY UPDATE Estado='Dropped'";
    }
    if($status=="5"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado) VALUES(".$userid.",".$idlibro.",'Plan to Read') ON DUPLICATE KEY UPDATE Estado='Plan to Read'";
    }               
    $result = $db->query($sql);  
    if($result){  
        echo "<script>window.location='../view/libro.php?idlibro=".$idlibro."'</script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($db);  
    }   
?>