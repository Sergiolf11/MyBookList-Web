<?php 
include($_SERVER['DOCUMENT_ROOT']."/MyBookList/config/conexion.php");
session_start();
    $username = $_POST['username'];  
    $password = $_POST['password'];  
    $con = new mysqli('localhost', 'root', '', 'mybooklist');

    //to prevent from mysqli injection  
    $username = stripcslashes($username);  
    $password = stripcslashes($password);  
    $username = mysqli_real_escape_string($con, $username);  
    $password = mysqli_real_escape_string($con, $password);  
  
    $sql = "select * from usuario where username = '$username' and password = '$password'";  
    $result = mysqli_query($con, $sql);  
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
    $count = mysqli_num_rows($result);  
    if($count == 1){  
        $_SESSION['userid'] = $row["Id_User"];
        $_SESSION['user'] = $row["Username"];
        $_SESSION['rol'] = $row["Rol"];
        $_SESSION['email'] = $row["Email"];
        $_SESSION['fotoPerfil'] = $row["FotoPerfil"];

        echo "<script>window.location='../view/home.php' </script>";  
    }  
    else{  
        echo "<scrit> alert('Usuario o contraseña incorrectos'); window.location='login.php' </script>";  
    }     
?>