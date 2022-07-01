<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../view/login.php");
}
$userid = $_SESSION['userid'];
$rol = $_SESSION['rol'];
$titulo = $_POST['titulo'];  
    $autor = $_POST['autor'];  
    $saga = $_POST['saga'];  
    $numSaga = $_POST['numSaga'];  
    $sinopsis = $_POST['sinopsis'];  
    $portada = $_POST['portada'];  

function getTitulo(){
    $cont=0;
    $titulo = strtolower($_POST['titulo']);  
    $titulo=str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'),
        array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'),
        $titulo );
    
    $url="https://www.amazon.es/s?k=".strtr($titulo,' ','+')."&i=stripbooks";
    while($cont<5){

        $content=file_get_contents($url);
        $posible_url=substr($content,strpos($content,'<span class="a-size-medium a-color-base a-text-normal">')+1);
        $posible_url=substr($posible_url,strpos($posible_url,'<span class="a-size-medium a-color-base a-text-normal">'));
        $pos_final=strpos($posible_url,'</span>');
        
        $posible_titulo=substr($posible_url,0,$pos_final);
        $posible_titulo=str_replace(
            array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'),
            array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'),
            $posible_titulo );
        $posible_titulo=strtolower($posible_titulo);
    
        if(str_contains($posible_titulo,$titulo)==true){
            return true;
        }
        $cont++;
    }
    return false;
}

function getAutor(){
    $cont=0;
    $titulo = strtolower($_POST['titulo']);  
    $titulo=str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'),
        array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'),
        $titulo );
    $autor = strtolower($_POST['autor']);
    $autor=str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'),
        array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'),
        $autor );
    $url="https://www.amazon.es/s?k=".strtr($titulo,' ','+')."&i=stripbooks";

    while($cont<5){
        $content=file_get_contents($url);
        $pos=strlen('<span class="a-size-base">de </span><span class="a-size-base">');
        //$posible_url=substr($content,strpos($content,'</a> </h2><div class="a-row a-size-base a-color-secondary"><div class="a-row">'));
        $posible_url=substr($content,strpos($content,'<span class="a-size-base">de')+$pos);
        $posible_url=substr($posible_url,strpos($posible_url,'<span class="a-size-base s-light-weight-text">'));
        $pos_final=strpos($posible_url,'</span>');
        
        $posible_autor=substr($posible_url,0,$pos_final);
        $posible_autor=str_replace(
            array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'),
            array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'),
            $posible_autor );
            $posible_autor=strtolower($posible_autor);
        if(str_contains($posible_autor,$autor)){
            return true;
        }
    $cont++;
    }
    return false;
}

$con = new mysqli('localhost', 'root', '', 'mybooklist');
$con->set_charset("utf8");

//to prevent from mysqli injection  
$titulo = stripcslashes($titulo);  
$autor = stripcslashes($autor);  
$saga = stripcslashes($saga);  
$numSaga = stripcslashes($numSaga);  
$sinopsis = stripcslashes($sinopsis);  

$titulo = mysqli_real_escape_string($con, $titulo);  
$autor = mysqli_real_escape_string($con, $autor);  
$saga = mysqli_real_escape_string($con, $saga);  
$numSaga = mysqli_real_escape_string($con, $numSaga);  
$sinopsis = mysqli_real_escape_string($con, $sinopsis);  

if($rol=="1"){
    $sqlregister = "INSERT INTO libro (Titulo, Autor, Saga, Num_Saga, Sinopsis, Portada) VALUES ('$titulo','$autor','$saga','$numSaga','$sinopsis','$portada')";  
    $result = mysqli_query($con, $sqlregister);     
    if($result){  
        $sqllibro="select * from libro where Titulo = '$titulo' and Autor='$autor' and Saga='$saga'";
        $result = mysqli_query($con, $sqllibro);
        $row = $result->fetch_assoc();
        echo "<script>window.location='../view/libro.php?idlibro=".$row["Id_Libro"]."' </script>";    
    }  
    else{  
        echo "Error: ".$sql."<br>".$mysql_error($con);  
    }   
}else if($rol=="0"){
    if(getTitulo() && getAutor()){
        $sqlregister = "INSERT INTO libro (Titulo, Autor, Saga, Num_Saga, Sinopsis, Portada) VALUES ('$titulo','$autor','$saga','$numSaga','$sinopsis','$portada')";  
        $result = mysqli_query($con, $sqlregister);     
        if($result){  
            $sqllibro="select * from libro where Titulo = '$titulo' and Autor='$autor' and Saga='$saga'";
            $result = mysqli_query($con, $sqllibro);
            $row = $result->fetch_assoc();
            echo "<script>window.location='../view/libro.php?idlibro=".$row["Id_Libro"]."' </script>";    
        }  
        else{  
            echo "Error: ".$sql."<br>".$mysql_error($con);  
        }    
    }else{
        echo "Lo sentimos, no hemos podido encontrar ese libro";
    }
}
?>