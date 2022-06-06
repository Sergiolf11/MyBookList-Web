<?php

    function getAllFromUser(){
        session_start();
        $userid = $_SESSION['userid'];
        $con = new mysqli('localhost', 'root', '', 'mybooklist');                    
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid'";  
        $result = mysqli_query($con, $sql);  

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "
                <br>
                <li class='list-group-item'>
                <div class='media'>
                    <div class='pull-left'>
                        <a href='../controller/libro.php?idlibro=".$row["Id_Libro"]."'><img class='img-rounded' src='".$row["Portada"]."'alt='...''></a>
                    </div>
                    <div class='media-body'>
                        <h4 class='media-heading'>".$row["Titulo"]." <small>".$row["Saga"]."".$row["Num_Saga"]." ".$row["Autor"]."</small> <small>".$row["Estado"]."</small></h4>
                        <div class='media-content'>
                        
                            <i class='fa fa-map-marker'></i> ".$row["Sinopsis"]."
                            
                        </div>
                    </div>
                </div>
            </li>";
            }
        } else {
            echo "0 results";
        }
        $con->close();
    }

    function getAllFromUserWhereStatus(){
        session_start();
        $userid = $_SESSION['userid'];
        $con = new mysqli('localhost', 'root', '', 'mybooklist');                    
        $status = !empty($_GET['status']) ? $_GET['status'] : "0";
        if($status=="0"){
            $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid'";  
        }
        if($status=="1"){
            $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Reading'";  
        }
        if($status=="2"){
            $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Completed'";  
        }
        if($status==3){
            $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'On Hold'";  
        }
        if($status=="4"){
            $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Dropped'";  
        }
        if($status=="5"){
            $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Plan to Read'";  
        }
        $result = mysqli_query($con, $sql);  

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "
                <br>
                <li class='list-group-item'>
                <div class='media'>
                    <div class='pull-left'>
                        <a href='../controller/libro.php?idlibro=".$row["Id_Libro"]."'><img class='img-rounded' src='".$row["Portada"]."'alt='...''></a>
                    </div>
                    <div class='media-body'>
                        <h4 class='media-heading'>".$row["Titulo"]." <small>".$row["Saga"]."".$row["Num_Saga"]." ".$row["Autor"]."</small> <small>".$row["Estado"]."</small></h4>
                        <div class='media-content'>
                        
                            <i class='fa fa-map-marker'></i> ".$row["Sinopsis"]."
                            
                        </div>
                    </div>
                </div>
            </li>";
            }
        } else {
            echo "0 results";
        }
        $con->close();
        
        
    }

?>