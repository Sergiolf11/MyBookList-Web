<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    // Include the database config file 
    include '../../config/conexion.php'; 
    $userid = $_SESSION['userid'];
    $password = $_POST['password'];  
    $newpassword1 = $_POST['newpassword1'];  
    $newpassword2 = $_POST['newpassword2']; 


    if($password=="" || $newpassword1=="" || $newpassword2==""){
       
    } else {
        $sql = "select * from usuario where Id_User = '$userid'";  
        $result = $db->query($sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
        
        if(password_verify($password,$row["Password"]) && $newpassword1==$newpassword2){  
            $pwd=password_hash($newpassword1,PASSWORD_DEFAULT);
            $sql2 = "UPDATE usuario SET Password='$pwd' WHERE Id_User=$userid";  
            $result = $db->query($sql2);   
            if($result){  
                echo "<script>localStorage.setItem('incorrectPass','false');</script>";
                echo "<script>window.location='../view/perfil.php' </script>";  
            }  
            else{  
                echo "Error: ".$sql2."<br>".$mysql_error($db);  
            }   
        }else{
            echo "<script>localStorage.setItem('incorrectPass','true');</script>";
            echo "<script>window.location='../view/editarPerfil.php' </script>"; 
        }
    }
?>