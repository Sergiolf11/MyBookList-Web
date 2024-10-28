<?php
function getAll(){
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
   // Include the database config file 
    include '../../config/conexion.php'; 

    $search = !empty($_GET['search']) ? $_GET['search'] : "";
    $genero = !empty($_GET['genero']) ? $_GET['genero'] : "";

    $limit = 40; // Número de registros por página
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Número de la página actual
    $offset = ($page - 1) * $limit; // Calcular el offset

    // Consulta para contar el total de registros
    $totalSql = "SELECT COUNT(*) as count FROM libro WHERE 1=1";
    
    
    if (!empty($search)) {
        $totalSql .= " AND (Titulo LIKE '%$search%' OR Autor LIKE '%$search%' OR Saga LIKE '%$search%')";
        $filter = 'search';
        $filterData = $_GET['search'];
    }

    if (!empty($genero)) {
        $totalSql .= " AND Genero = '$genero'";
        $filter = 'genero';
        $filterData = $_GET['genero'];
    }

    $totalResult = $db->query($totalSql);
    $totalCount = $totalResult->fetch_assoc()['count'];
    $totalPages = ceil($totalCount / $limit); // Calcular total de páginas

    /// Consulta principal
    $sql = "SELECT * FROM libro WHERE 1=1";

    if (!empty($search)) {
        $sql .= " AND (Titulo LIKE '%$search%' OR Autor LIKE '%$search%' OR Saga LIKE '%$search%')";
    }

    if (!empty($genero)) {
        $sql .= " AND Genero = '$genero'";
    }

    $sql .= " ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2), Saga, Num_Saga LIMIT $limit OFFSET $offset";

    $result = $db->query($sql);  

    echo "<div class='row'>";
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

    // Paginación
    echo "</div><nav aria-label='Paginación'><ul class='pagination justify-content-center'>";

    // Enlace "Anterior"
    echo "<li class='page-item " . ($page <= 1 ? 'disabled' : '') . "'>
            <a class='page-link' href='?page=" . ($page - 1) . "&".$filter."=" . $filterData . "'>Anterior</a>
          </li>";

    // Enlaces de páginas
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>
                <a class='page-link' href='?page=" . $i . "&".$filter."=" . $filterData . "'>" . $i . "</a>
              </li>";
    }

    // Enlace "Siguiente"
    echo "<li class='page-item " . ($page >= $totalPages ? 'disabled' : '') . "'>
            <a class='page-link' href='?page=" . ($page + 1) . "&".$filter."=" . $filterData . "'>Siguiente</a>
          </li>";

    echo "</ul></nav>";
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
    $genero = !empty($_GET['genero']) ? $_GET['genero'] : "0";
    $year = !empty($_GET['year']) ? $_GET['year'] : "0";
    $stars = !empty($_GET['stars']) ? $_GET['stars'] : "0";


    // Configuración de paginación
    $limit = 40; // Número de registros por página
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
    $offset = ($page - 1) * $limit; // Calcular offset

    // Consulta para contar el total de registros
    $totalSql = "SELECT COUNT(*) as count FROM usuario_libro ul JOIN libro l ON ul.Id_Libro=l.Id_Libro WHERE Id_User = '$userid'";
    

    if ($status !== "0") {
        $totalSql .= " AND Estado = (CASE 
                        WHEN '$status' = '1' THEN 'Reading'
                        WHEN '$status' = '2' THEN 'Completed'
                        WHEN '$status' = '3' THEN 'On Hold'
                        WHEN '$status' = '4' THEN 'Dropped'
                        WHEN '$status' = '5' THEN 'Plan to Read'
                        END)";
        $filter = 'status';
        $filterData = $_GET['status'];
    }

    if ($genero !== "0") {
        $totalSql .= " AND l.Genero = '$genero'";
        $filter = 'genero';
        $filterData = $_GET['genero'];
    }

    if ($year !== "0") {
        $totalSql .= " AND YEAR(ul.Fecha_Fin) = '$year'";
        $filter = 'year';
        $filterData = $_GET['year'];
    }

    if ($stars !== "0") {
        $totalSql .= " AND ul.Estrellas = '$stars'";
        $filter = 'stars';
        $filterData = $_GET['stars'];
    }

    $totalResult = $db->query($totalSql);
    $totalCount = $totalResult->fetch_assoc()['count'];
    $totalPages = ceil($totalCount / $limit); // Total de páginas

    // Consulta principal
    $sql = "SELECT * FROM usuario_libro ul JOIN libro l ON ul.Id_Libro=l.Id_Libro WHERE Id_User = '$userid'";
        
    if ($status !== "0") {
        $sql .= " AND Estado = (CASE 
                    WHEN '$status' = '1' THEN 'Reading'
                    WHEN '$status' = '2' THEN 'Completed'
                    WHEN '$status' = '3' THEN 'On Hold'
                    WHEN '$status' = '4' THEN 'Dropped'
                    WHEN '$status' = '5' THEN 'Plan to Read'
                    END)";
    }

    if ($genero !== "0") {
        $sql .= " AND l.Genero = '$genero'";
    }

    if ($stars !== "0") {
        $sql .= " AND ul.Estrellas = '$stars'";
    }

    if ($year !== "0") {
        $sql .= " AND YEAR(ul.Fecha_Fin) = '$year' ORDER BY Fecha_Fin ASC LIMIT $limit OFFSET $offset";
    }else{
        $sql .= " ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2), Saga, Num_Saga LIMIT $limit OFFSET $offset";
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

    // Paginación
    echo "</div><nav aria-label='Paginación'><ul class='pagination justify-content-center'>";

    // Enlace "Anterior"
    echo "<li class='page-item " . ($page <= 1 ? 'disabled' : '') . "'>
            <a class='page-link' href='?page=" . ($page - 1) . "&".$filter."=" . $filterData . "'>Anterior</a>
          </li>";

    // Enlaces de páginas
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>
                <a class='page-link' href='?page=" . $i . "&".$filter."=" . $filterData . "'>" . $i . "</a>
              </li>";
    }

    // Enlace "Siguiente"
    echo "<li class='page-item " . ($page >= $totalPages ? 'disabled' : '') . "'>
            <a class='page-link' href='?page=" . ($page + 1) . "&".$filter."=" . $filterData . "'>Siguiente</a>
          </li>";

    echo "</ul></nav>";
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