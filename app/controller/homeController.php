<?php
function getAll(){
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
   // Include the database config file 
    include '../../config/conexion.php'; 
    $search = !empty($_GET['search']) ? $_GET['search'] : "0";
    if($search=="0"){
        $sql = "select * from libro  ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else{
        $sql = "select * from libro where Titulo LIKE '%$search%' or Autor LIKE '%$search%' or Saga LIKE '%$search%' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }
    
    $result = $db->query($sql);  

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["Num_Saga"] == 0){
                $SagaNum="";
            }else{
                $SagaNum="<p class='designation'>".$row["Saga"]." ".$row["Num_Saga"]."</p>";
            }
            echo "
            <div class='col-12 col-sm-6 col-lg-3'>
            <a  href='libro.php?idlibro=".$row["Id_Libro"]."'>
                <div class='single_advisor_profile wow fadeInUp' data-wow-delay='0.3s' style='visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;'>
                    <div class='advisor_thumb'><img src='".$row["Portada"]."' width='200' height='300' alt=''></div>
                    <div class='single_advisor_details_info '>
                        <h6>".$row["Titulo"]."</h6>
                        <p class='designation'>".$row["Autor"]."</p>
                        ".$SagaNum."
                    </div>
                </div>
                </a>
            </div>
            ";
        }
    } else {
        echo "0 results";
    }
}

function getAllFromUser(){
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $status = !empty($_GET['status']) ? $_GET['status'] : "0";
    if($status=="0"){
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else if($status=="1"){
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Reading' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else if($status=="2"){
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Completed' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else if($status=="3"){
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'On Hold' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else if($status=="4"){
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Dropped' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else if($status=="5"){
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid' and Estado = 'Plan to Read' ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }else{
        $sql = "select * from usuario_libro ul join libro l on ul.Id_Libro=l.Id_Libro where Id_User = '$userid'  ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2),Saga,Num_Saga";  
    }

    $result = $db->query($sql);  

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["Num_Saga"] == 0){
                $SagaNum="";
            }else{
                $SagaNum="<p class='designation'>".$row["Saga"]." ".$row["Num_Saga"]."</p>";
            }
            echo "
            <div class='col-12 col-sm-6 col-lg-3'>
            <a  href='libro.php?idlibro=".$row["Id_Libro"]."'>
                <div class='single_advisor_profile wow fadeInUp' data-wow-delay='0.3s' style='visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;'>
                    <div class='advisor_thumb '><img src='".$row["Portada"]."'  width='200' height='300' alt=''></div>
                    <div class='single_advisor_details_info '>
                        <h6>".$row["Titulo"]."</h6>
                        <p class='designation'>".$row["Autor"]."</p>
                        ".$SagaNum."
                        <p>".$row["Estado"]."</p>
                    </div>
                </div>
                </a>
            </div>
            ";
        }
    } else {
        echo "Esta lista esta vacia";
    }
}

function getCount(){
    // Include the database config file 
    include '../../config/conexion.php'; 
    $sql = "select COUNT(*) from libro";
    $result = $db->query($sql);  
    $data=mysqli_fetch_row($result)[0];
    echo "<p>We currently have ".$data." books.</p>";
}
?>