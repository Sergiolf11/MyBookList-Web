<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="home.php">Home</a>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="table.php?status=1">MyList <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="anadir.php">Añadir</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="perfil.php">Perfil</a>
      </li>

        <?php
        // Obtener el rol desde la variable de entorno
        $rol = $_SESSION['rol'];
        // Si el rol es 1
            // Conectar a la base de datos
            include '../../config/conexion.php';
            // Buscar un libro aleatorio con algún campo NULL
            $query = "SELECT l.Id_Libro FROM libro l JOIN usuario_libro ul ON l.Id_Libro = ul.Id_Libro WHERE ( l.Editorial IS NULL OR l.Portada IS NULL OR l.Num_Saga IS NULL OR Sinopsis IS NULL ) AND ul.Estado != 'Plan to Read' LIMIT 1";
            $result = $db->query($query);
            // Verificar si se encontró un libro
            if ($row = $result->fetch_assoc()) {
                $idLibro = $row['Id_Libro'];

                // Mostrar el enlace solo si hay un libro con campos NULL
                echo "<li class='nav-item'>";
                echo "<a class='nav-link' href='editarLibro.php?idlibro=".$idLibro."'>Correccion</a>";
                echo "</li>";
            }
        ?>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle"
                    type="button" id="dropdownMenu1" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                Generos
            </button>
            <div class="dropdown-menu" style='min-width: max-content;' aria-labelledby="dropdownMenu1">
                <?php
                session_start();
                // Include the database config file 
                include '../../config/conexion.php'; 
                $sqlGeneros  = "select * from genero order by Genero ASC";
                $resultGeneros  = $db->query($sqlGeneros);  
                $generos = [];
                $contador = 0; // Inicializar el contador

                echo '<div class="dropdown-row">'; // Abrir el primer div

                while ($rowGenero = $resultGeneros->fetch_assoc()) {
                    if ($contador % 3 == 0 && $contador > 0) {
                        // Si el contador es divisible por 4 y no es cero, cierra y abre un nuevo div
                        echo '</div>';
                        echo '<div class="dropdown-row">';
                    }

                    $generos[] = $rowGenero;
                    echo "<a class='dropdown-item' style='display: inline-block; width: 30%;' href='./home.php?genero={$rowGenero['Id_Genero']}'>{$rowGenero['Genero']}</a>";
                    $contador++;
                }

                echo '</div>'; // Cerrar el último div
                ?>
            </div>
        </div>
    </div>
    </ul>
    <ul style="padding-left:65%;" class="navbar-nav navbar-right mr-auto mt-2 mt-lg-0">
      <li class="nav-item">
        <a class="nav-link"  href="../controller/logoutController.php"><span class="glyphicon glyphicon-log-out"></span> LogOut</a>
      </li>
    </ul>
  </div>
</nav>
<br><br><br>