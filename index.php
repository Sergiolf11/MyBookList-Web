<?php
if(isset($_SESSION['user'])){
    //echo "hay sesion";
    header("Location:app/view/home.php");

}else{
    //echo "login";
    header("Location:app/view/login.php");
}

//SI FALLA ASI "Connection failed: No such file or directory" CAMBIA LOS VALORES DE CONEXION 

?>