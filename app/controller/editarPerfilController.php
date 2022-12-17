<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    // Include the database config file 
    include '../../config/conexion.php'; 
    $userid = $_SESSION['userid'];
    $username = $_POST['username'];  
    $fotoPerfil = $_POST['fotoPerfil'];  
    $email = $_POST['email']; 
    if($username==""){
        $username=$_SESSION['username'];
    }
    if($fotoPerfil==""){
        $fotoPerfil=$_SESSION['fotoPerfil'];
    }
    if($fotoPerfil=="default"){
        $fotoPerfil=$_SESSION['fotoPerfil'];
    }
    if($email==""){
        $email=$_SESSION['email'];
    }

    $sql = "UPDATE usuario SET Username='$username',Email='$email',FotoPerfil='$fotoPerfil' WHERE Id_User=$userid";  
    $result = $db->query($sql);  
    if($result){  
        echo "<script>window.location='../view/perfil.php' </script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($db);  
    }   
?>