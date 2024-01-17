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
    $sql1 = "select * from usuario_libro ul join usuario u on ul.Id_User=ul.Id_User where u.Id_User = '$userid' and Id_Libro = '$idlibro'";  
    $result1 = $db->query($sql1); 
    $row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    if($status=="1"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado, Fecha_Inicio, Fecha_Fin) VALUES(".$userid.",".$idlibro.",'Reading',CURDATE(),'".$row['Fecha_Fin']."') ON DUPLICATE KEY UPDATE Estado='Reading', Fecha_Inicio=CURDATE()";
    }
    if($status=="2"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado, Fecha_Inicio, Fecha_Fin) VALUES(".$userid.",".$idlibro.",'Completed','".$row['Fecha_Inicio']."',CURDATE()) ON DUPLICATE KEY UPDATE Estado='Completed', Fecha_Fin=CURDATE()";
    }
    if($status=="3"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado, Fecha_Inicio, Fecha_Fin) VALUES(".$userid.",".$idlibro.",'On Hold','".$row['Fecha_Inicio']."','".$row['Fecha_Fin']."') ON DUPLICATE KEY UPDATE Estado='On Hold'";
    }
    if($status=="4"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado, Fecha_Inicio, Fecha_Fin) VALUES(".$userid.",".$idlibro.",'Dropped','".$row['Fecha_Inicio']."','".$row['Fecha_Fin']."') ON DUPLICATE KEY UPDATE Estado='Dropped'";
    }
    if($status=="5"){
        $sql = "INSERT INTO usuario_libro (Id_User, Id_Libro, Estado, Fecha_Inicio, Fecha_Fin) VALUES(".$userid.",".$idlibro.",'Plan to Read','".$row['Fecha_Inicio']."','".$row['Fecha_Fin']."') ON DUPLICATE KEY UPDATE Estado='Plan to Read'";
    }               
    $result = $db->query($sql);  
    if($result){  
        echo "<script>window.location='../view/libro.php?idlibro=".$idlibro."'</script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($db);  
    }   
?>