<?php 
include($_SERVER['DOCUMENT_ROOT']."/MyBookList/config/conexion.php");
session_start();
    $username = $_POST['username'];  
    $password = $_POST['password'];  
    $email = $_POST['email'];  
    $con = new mysqli('localhost', 'root', '', 'mybooklist');

    //to prevent from mysqli injection  
    $username = stripcslashes($username);  
    $password = stripcslashes($password);  
    $email = stripcslashes($email);  
    $username = mysqli_real_escape_string($con, $username);  
    $password = mysqli_real_escape_string($con, $password);  
    $email = mysqli_real_escape_string($con, $email);  
  
    $sqlregister = "INSERT INTO usuario (Username, Password, Email) VALUES ('$username','$password','$email')";  
    $result = mysqli_query($con, $sqlregister);     
    if($result){  
        echo "<script>window.location='../view/login.php' </script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($con);  
    }     
?>