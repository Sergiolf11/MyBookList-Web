<?php
session_start();
if(isset($_SESSION['user'])){
    //echo "hay sesion";
    header("Location:app/view/home.php");
    exit;

}else{
    //echo "login";
    header("Location:app/view/login.php");
    exit;
}

//SI FALLA ASI "Connection failed: No such file or directory" CAMBIA LOS VALORES DE CONEXION 

?>