<?php
    session_start();
    
    $userid = $_SESSION['userid'];
    $con = new mysqli('localhost', 'root', '', 'mybooklist');    
    $idlibro = $_GET['idLibro'];
    $status =$_GET['status'];
    echo $status;
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
    $result = mysqli_query($con, $sql); 
    if($result){  
        echo "<script>window.location='../view/table.php'</script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($con);  
    }   
    $con->close();



?>