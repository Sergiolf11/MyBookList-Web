<?php 
session_start();
    //to prevent from mysqli injection  
    include '../../config/conexion.php'; 
    $username = $_POST['username'];  
    $password = $_POST['password'];  

    $username = stripcslashes($username);  
    $password = stripcslashes($password);  
   // $username = mysqli_real_escape_string($bd, $username);  
  //  $password = mysqli_real_escape_string($bd, $password);  
  
    $sql = "select * from usuario where username = '$username'";  
    $result = $db->query($sql);  
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
    
    if(password_verify($password,$row["Password"])){  
        $_SESSION['userid'] = $row["Id_User"];
        $_SESSION['user'] = $row["Username"];
        $_SESSION['rol'] = $row["Rol"];
        $_SESSION['email'] = $row["Email"];
        $_SESSION['fotoPerfil'] = $row["FotoPerfil"];
        echo "<script>localStorage.setItem('denegado','false');</script>";
        echo "<script>window.location='../view/home.php' </script>";  
    }  
    else{  
        echo "<script>localStorage.setItem('denegado','true');</script>";
        echo "<script>window.location='../view/login.php' </script>"; 
    }     
?>