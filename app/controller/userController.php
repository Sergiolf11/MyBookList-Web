<?php
function getFirstBox(){
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    $rol = $_SESSION['rol'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $sql = "select * from usuario where Id_User='$userid'";  
    $result = $db->query($sql);   
   
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["Rol"]=="0"){
                $rol="User";
            }
            if($row["Rol"]=="1"){
                $rol="Mod";
            }
            echo "
            <img src='".$row["FotoPerfil"]."' class='rounded-circle' width='150'>
            <div class='mt-3'>
              <h4>".$row["Username"]."</h4>
              <p class='text-secondary mb-1'>Rol: ".$rol."</p>
              <br>
            </div>
            ";
        }
    } else {
        echo "0 results";
    }
}

function getSecondBox(){
    //session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $sql = "select * from usuario_libro where Id_User='$userid'";  
    $result = $db->query($sql);   
    // Obtener el año actual
    $ano_actual = date("Y");

    // Consulta SQL para obtener el COUNT de libros donde Fecha_Fin sea el año actual
    $sqlcount = "select COUNT(*) AS num_libros from usuario_libro where Id_User='$userid' AND YEAR(Fecha_Fin) = YEAR(NOW())";  
    $resultcount = $db->query($sqlcount);   
    $reading=0;
    $completed=0;
    $onHold=0;
    $dropped=0;
    $planToRead=0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["Estado"]=="Reading"){
                $reading++;
            }
            if($row["Estado"]=="Completed"){
                $completed++;
            }
            if($row["Estado"]=="On Hold"){
                $onHold++;
            }
            if($row["Estado"]=="Dropped"){
                $dropped++;
            }
            if($row["Estado"]=="Plan to Read"){
                $planToRead++;
            }
        }
        $preading= ($reading!=0) ? ($reading/$result->num_rows)*100 : 0;
        $pcompleted= ($completed!=0) ? ($completed/$result->num_rows)*100 : 0;
        $ponHold= ($onHold!=0) ? ($onHold/$result->num_rows)*100 : 0;
        $pdropped= ($dropped!=0) ? ($dropped/$result->num_rows)*100 : 0;
        $pplanToRead= ($planToRead!=0) ? ($planToRead/$result->num_rows)*100 : 0;
        echo "
        <h3>Book Stats: ".$result->num_rows."</h3><br>
        <div class='progress'>
            <div class='progress-bar bg-success' role='progressbar' style='width: ".$preading."%' aria-valuenow='".$preading."' aria-valuemin='0' aria-valuemax='100'></div>
            <div class='progress-bar bg-primary' role='progressbar' style='width: ".$pcompleted."%' aria-valuenow='".$pcompleted."' aria-valuemin='0' aria-valuemax='100'></div>
            <div class='progress-bar bg-warning' role='progressbar' style='width: ".$ponHold."%' aria-valuenow='".$ponHold."' aria-valuemin='0' aria-valuemax='100'></div>
            <div class='progress-bar bg-danger' role='progressbar' style='width: ".$pdropped."%' aria-valuenow='".$pdropped."' aria-valuemin='0' aria-valuemax='100'></div>
            <div class='progress-bar bg-secondary' role='progressbar' style='width: ".$pplanToRead."%' aria-valuenow='".$pplanToRead."' aria-valuemin='0' aria-valuemax='100'></div>
        </div>

        <br>
        <div  style='width: 100%; height: 10px;'>
            <div class='bg-success' style='float:left; width: 15px; height: 15px; border-radius: 50%;'>&nbsp;</div>
            <p style='margin-bottom :0; float:left;'>&nbsp; <a href='../view/table.php?status=1'>Reading</a>: &emsp;&ensp;&nbsp;".$reading."</p>
            <br>
            <div class='bg-primary' style='float:left; width: 15px; height: 15px; border-radius: 50%;'>&nbsp;</div>
            <p style='margin-bottom :0; float:left;'>&nbsp;  <a href='../view/table.php?status=2'>Completed</a>: &ensp;".$completed."</p>
            <br>
            <div class='bg-warning' style='float:left; width: 15px; height: 15px; border-radius: 50%;'>&nbsp;</div>
            <p style='margin-bottom :0; float:left;'>&nbsp;  <a href='../view/table.php?status=3'>On Hold</a>: &emsp;&ensp;&nbsp;".$onHold."</p>
            <br>
            <div class='bg-danger' style='float:left; width: 15px; height: 15px; border-radius: 50%;'>&nbsp;</div>
            <p style='margin-bottom :0; float:left;'>&nbsp;  <a href='../view/table.php?status=4'>Dropped</a>: &emsp;&ensp;".$dropped."</p>
            <br>
            <div class='bg-secondary' style='float:left; width: 15px; height: 15px; border-radius: 50%;'>&nbsp;</div>
            <p style='margin-bottom :0; float:left;'>&nbsp;  <a href='../view/table.php?status=5'>Plan to Read</a>:&nbsp;".$planToRead."</p>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>";

        if ($resultcount) {
            // Obtener el resultado como un arreglo asociativo
            $fila = $resultcount->fetch_assoc();

            // Obtener el COUNT de libros
            $numLibros = $fila['num_libros'];
            echo "
            
            <a style='color: inherit;' href='../view/table.php?year=".$ano_actual."'><p>Libros leidos este año: ".$numLibros."</p></a>";
        }else{
            echo "".$numLibros."";
        }
    } else {
        echo "0 results";
    }
    
}

function form1(){
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $sql = "select * from usuario where Id_User='$userid'";  
    $result = $db->query($sql);  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row['FotoPerfil']=="../../public/img/defaultProfile.jpg"){
                $foto="default";
            }else{
                $foto=$row['FotoPerfil'];
            }
            echo "<div class='row mb-3'>
            <div class='col-sm-3'>
                <h6 class='mb-0'>Username</h6>
            </div>
            <div class='col-sm-9 text-secondary'>
                <input type='text' name='username' placeholder='Username' class='form-control' value='".$row['Username']."'>
            </div>
            </div>
            <div class='row mb-3'>
                <div class='col-sm-3'>
                    <h6 class='mb-0'>Email</h6>
                </div>
                <div class='col-sm-9 text-secondary'>
                    <input type='email' name='email' placeholder='Email' class='form-control' value='".$row['Email']."'>
                </div>
            </div>
            <div class='row mb-3'>
                <div class='col-sm-3'>
                    <h6 class='mb-0'>Foto de Perfil</h6>
                </div>
                <div class='col-sm-9 text-secondary'>
                    <input type='text' name='fotoPerfil' placeholder='Profile Picture URL' class='form-control' value='".$foto."'>
                </div>
            </div>
       
            <div class='row'>
                <div class='col-sm-3'></div>
                <div class='col-sm-9 text-secondary'>
                    <input type='submit' class='btn btn-primary px-4' value='Save Changes'>
                </div>
            </div>";  
        }
    }else{
        echo "0 results";
    }
}

function form2(){
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
    $userid = $_SESSION['userid'];
    // Include the database config file 
    include '../../config/conexion.php'; 
    $sql = "select * from usuario where Id_User='$userid'";  
    $result = $db->query($sql);  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='row mb-3'>
            <div class='col-sm-3'>
                <h6 class='mb-0'>Contraseña</h6>
            </div>
            <div class='col-sm-9 text-secondary'>
                <input type='password' name='password' placeholder='Contraseña' class='form-control'>
            </div>
            </div>
            <div class='row mb-3'>
                <div class='col-sm-3'>
                    <h6 class='mb-0'>Nueva contraseña</h6>
                </div>
                <div class='col-sm-9 text-secondary'>
                    <input type='password' name='newpassword1' placeholder='Nueva contraseña' class='form-control'>
                </div>
            </div>
            <div class='row mb-3'>
                <div class='col-sm-3'>
                    <h6 class='mb-0'>Repetir contraseña</h6>
                </div>
                <div class='col-sm-9 text-secondary'>
                    <input type='password' name='newpassword2' placeholder='Nueva contraseña' class='form-control'>
                </div>
            </div>
       
            <div class='row'>
                <div class='col-sm-3'></div>
                <div class='col-sm-9 text-secondary'>
                    <input type='submit' class='btn btn-primary px-4' value='Save Changes'>
                </div>
            </div>
				<p id='erroneo' style='color: red'>La contraseña es incorrecta o no coinciden las nuevas</p>";  
        }
    }else{
        echo "0 results";
    }
}
?>