<?php 
session_start();
    $username = $_POST['username'];  
    $password = $_POST['password'];  
    $email = $_POST['email'];  
    // Include the database config file 
    include '../../config/conexion.php'; 

    //to prevent from mysqli injection  
    $username = stripcslashes($username);  
    $password = stripcslashes($password);  
    $email = stripcslashes($email);  
    
    $username = mysqli_real_escape_string($con, $username);  
    $password = mysqli_real_escape_string($con, $password);  
    $email = mysqli_real_escape_string($con, $email);  
  
    $pwd=password_hash($password,PASSWORD_DEFAULT);

    $sql="select * from usuario where Email='$email' or Username='$username'";
    $result = $db->query($sql);  
    if($result->num_rows > 0){
        echo "<script>localStorage.setItem('denegado','true');</script>";
        echo "<script>window.location='../view/register.php' </script>"; 
    }else{
        $sqlregister = "INSERT INTO usuario (Username, Password, Email) VALUES ('$username','$pwd','$email')";  
        if(mysqli_query($con, $sqlregister)){  
            echo "<script>localStorage.setItem('denegado','false');</script>";
            echo "<script>window.location='../view/login.php' </script>";    
        }else{  
            echo "Error: ".$sql."<br>".$mysql_error($con);  
        }
    }
?>