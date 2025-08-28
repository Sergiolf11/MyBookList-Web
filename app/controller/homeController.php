<?php
function getAll(){
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location:../view/login.php");
    }
   // Include the database config file 
    include '../../config/conexion.php'; 

    $search = isset($_GET['search']) ? trim($_GET['search']) : "";
    $genero = isset($_GET['genero']) ? (int)$_GET['genero'] : 0;
    $editorial = isset($_GET['editorial']) ? trim($_GET['editorial']) : "";
    $filter = "";
    $filterData = "";

    $limit = 40; // Número de registros por página
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Número de la página actual
    $offset = ($page - 1) * $limit; // Calcular el offset

    // Consulta para contar el total de registros
    $totalSql = "SELECT COUNT(*) as count FROM libro WHERE 1=1";
    $params = [];
    $types = "";

    if ($search !== "") {
        $totalSql .= " AND (Titulo LIKE CONCAT('%', ?, '%') OR Autor LIKE CONCAT('%', ?, '%') OR Saga LIKE CONCAT('%', ?, '%'))";
        $params[] = $search; $params[] = $search; $params[] = $search; $types .= "sss";
        $filter = 'search';
        $filterData = $search;
    }

    if ($genero > 0) {
        $totalSql .= " AND Genero = ?";
        $params[] = $genero; $types .= "i";
        $filter = 'genero';
        $filterData = (string)$genero;
    }

    if ($editorial !== "") {
        $totalSql .= " AND Editorial = ?";
        $params[] = $editorial; $types .= "s";
        $filter = 'editorial';
        $filterData = $editorial;
    }

    $stmt = $db->prepare($totalSql);
    if ($types !== "") { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $totalResult = $stmt->get_result();
    $totalCount = $totalResult->fetch_assoc()['count'];
    $totalPages = max(1, (int)ceil($totalCount / $limit)); // Calcular total de páginas

    /// Consulta principal
    $sql = "SELECT Id_Libro, Titulo, Autor, Saga, Num_Saga, Portada FROM libro WHERE 1=1";
    $qParams = [];
    $qTypes = "";

    if ($search !== "") {
        $sql .= " AND (Titulo LIKE CONCAT('%', ?, '%') OR Autor LIKE CONCAT('%', ?, '%') OR Saga LIKE CONCAT('%', ?, '%'))";
        $qParams[] = $search; $qParams[] = $search; $qParams[] = $search; $qTypes .= "sss";
    }

    if ($genero > 0) {
        $sql .= " AND Genero = ?";
        $qParams[] = $genero; $qTypes .= "i";
    }

    if ($editorial !== "") {
        $sql .= " AND Editorial = ?";
        $qParams[] = $editorial; $qTypes .= "s";
    }

    $sql .= " ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2), Saga, Num_Saga LIMIT ? OFFSET ?";
    $qParams[] = $limit; $qParams[] = $offset; $qTypes .= "ii";

    $stmt = $db->prepare($sql);
    $stmt->bind_param($qTypes, ...$qParams);
    $stmt->execute();
    $result = $stmt->get_result();  

    echo "<div class='row'>";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["Num_Saga"] == 0){
                $SagaNum="";
            }else{
                $Saga = htmlspecialchars($row["Saga"], ENT_QUOTES, 'UTF-8');
                $SagaNum="<p class='designation'>".$Saga." ".$row["Num_Saga"]."</p>";
            }
            $Titulo = htmlspecialchars($row["Titulo"], ENT_QUOTES, 'UTF-8');
            $Autor = htmlspecialchars($row["Autor"], ENT_QUOTES, 'UTF-8');
            echo "
            <div class='col-12 col-sm-6 col-lg-3'>
            <a  href='libro.php?idlibro=".$row["Id_Libro"]."'>
                <div class='single_advisor_profile wow fadeInUp' data-wow-delay='0.3s' style='visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;'>
                    <div class='advisor_thumb'><img src='".$row["Portada"]."' width='200' height='300' alt=''></div>
                    <div class='single_advisor_details_info '>
                        <h6>".$Titulo."</h6>
                        <p class='designation'>".$Autor."</p>
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
    $status = isset($_GET['status']) ? (int)$_GET['status'] : 0;
    $genero = isset($_GET['genero']) ? (int)$_GET['genero'] : 0;
    $editorial = isset($_GET['editorial']) ? trim($_GET['editorial']) : "";
    $year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
    $stars = isset($_GET['stars']) ? (int)$_GET['stars'] : 0;


    // Configuración de paginación
    $limit = 40; // Número de registros por página
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Página actual
    $offset = ($page - 1) * $limit; // Calcular offset

    // Consulta para contar el total de registros
    $filter = '';
    $filterData = '';
    $where = " WHERE Id_User = ? ";
    $params = [$userid];
    $types = "i";

    if ($status > 0) {
        $where .= " AND Estado = (CASE WHEN ?=1 THEN 'Reading' WHEN ?=2 THEN 'Completed' WHEN ?=3 THEN 'On Hold' WHEN ?=4 THEN 'Dropped' WHEN ?=5 THEN 'Plan to Read' END)";
        $params = array_merge($params, array_fill(0, 5, $status));
        $types .= "iiiii";
        $filter = 'status';
        $filterData = (string)$status;
    }

    if ($genero > 0) {
        $where .= " AND l.Genero = ?";
        $params[] = $genero; $types .= "i";
        $filter = 'genero';
        $filterData = (string)$genero;
    }

    if ($editorial !== "") {
        $where .= " AND l.Editorial = ?";
        $params[] = $editorial; $types .= "s";
        $filter = 'editorial';
        $filterData = $editorial;
    }

    if ($year > 0) {
        $where .= " AND YEAR(ul.Fecha_Fin) = ?";
        $params[] = $year; $types .= "i";
        $filter = 'year';
        $filterData = (string)$year;
    }

    if ($stars > 0) {
        $where .= " AND ul.Estrellas = ?";
        $params[] = $stars; $types .= "i";
        $filter = 'stars';
        $filterData = (string)$stars;
    }

    $totalSql = "SELECT COUNT(*) as count FROM usuario_libro ul JOIN libro l ON ul.Id_Libro=l.Id_Libro" . $where;
    $stmt = $db->prepare($totalSql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $totalCount = $stmt->get_result()->fetch_assoc()['count'];
    $totalPages = max(1, (int)ceil($totalCount / $limit)); // Total de páginas

    // Consulta principal
    $sql = "SELECT ul.*, l.Id_Libro, l.Titulo, l.Autor, l.Saga, l.Num_Saga, l.Portada FROM usuario_libro ul JOIN libro l ON ul.Id_Libro=l.Id_Libro" . $where;
    $order = ($year > 0) ? " ORDER BY Fecha_Fin ASC" : " ORDER BY IF(Saga RLIKE '^[a-z]', 1, 2), Saga, Num_Saga";
    $sql .= $order . " LIMIT ? OFFSET ?";
    $params2 = array_merge($params, [$limit, $offset]);
    $types2 = $types . "ii";
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types2, ...$params2);
    $stmt->execute();
    $result = $stmt->get_result();  

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["Num_Saga"] == 0){
                $SagaNum="";
            }else{
                $SagaNum="<p class='designation'>".$row["Saga"]." ".$row["Num_Saga"]."</p>";
            }
            $Titulo = htmlspecialchars($row["Titulo"], ENT_QUOTES, 'UTF-8');
            $Autor = htmlspecialchars($row["Autor"], ENT_QUOTES, 'UTF-8');
            $SagaTxt = htmlspecialchars($row["Saga"], ENT_QUOTES, 'UTF-8');
            $Estado = htmlspecialchars($row["Estado"], ENT_QUOTES, 'UTF-8');
            echo "
            <div class='col-12 col-sm-6 col-lg-3'>
            <a  href='libro.php?idlibro=".$row["Id_Libro"]."'>
                <div class='single_advisor_profile wow fadeInUp' data-wow-delay='0.3s' style='visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;'>
                    <div class='advisor_thumb '><img src='".$row["Portada"]."'  width='200' height='300' alt=''></div>
                    <div class='single_advisor_details_info '>
                        <h6>".$Titulo."</h6>
                        <p class='designation'>".$Autor."</p>
                        ".$SagaNum."
                        <p>".$Estado."</p>
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