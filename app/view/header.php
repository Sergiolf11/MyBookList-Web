<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" data-theme="dark">
  <!-- Botón del menú lateral (visible en todos los tamaños) -->
  <button class="sidebar-toggle" type="button" aria-label="Abrir menú lateral">
    <i class="fa fa-bars"></i>
  </button>
  
  <!-- Botón del menú de navegación de Bootstrap (oculto en móvil, visible en desktop) -->
  <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation" style="display: none;">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <a class="navbar-brand" href="home.php">Home</a>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <!-- Menú vacío - todas las opciones están en el sidebar -->
  </div>
</nav>

<!-- Menú lateral -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <h4>Menú</h4>
    <button class="sidebar-close" type="button" aria-label="Cerrar menú lateral">
      <i class="fa fa-times"></i>
    </button>
  </div>
  <div class="sidebar-content">
    <!-- Sección de Navegación Principal -->
    <div class="sidebar-section">
      <h5 class="sidebar-section-title">
        <i class="fa fa-navicon"></i> Navegación
      </h5>
      <div class="sidebar-list">
        <a href="home.php" class="sidebar-link">
          <i class="fa fa-home"></i> Home
        </a>
        <a href="anadir.php" class="sidebar-link">
          <i class="fa fa-plus"></i> Añadir
        </a>
        <a href="perfil.php" class="sidebar-link">
          <i class="fa fa-user"></i> Perfil
        </a>
        <a href="#" class="sidebar-link" data-toggle="modal" data-target="#scanModal">
          <i class="fa fa-barcode"></i> Escanear ISBN
        </a>
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
                echo "<a href='editarLibro.php?idlibro=".$idLibro."' class='sidebar-link'>";
                echo "<i class='fa fa-edit'></i> Correccion";
                echo "</a>";
            }
        ?>
        <a href="#" class="sidebar-link" id="sidebar-theme-toggle" onclick="event.preventDefault(); toggleThemeFromSidebar();">
          <i class="fa fa-moon-o" id="theme-icon"></i> <span id="theme-text">Modo Oscuro</span>
        </a>
        <a href="../controller/logoutController.php" class="sidebar-link">
          <i class="fa fa-sign-out"></i> LogOut
        </a>
      </div>
    </div>
    
    <!-- Sección de Filtros Rápidos -->
    <div class="sidebar-section">
      <h5 class="sidebar-section-title">
        <i class="fa fa-filter"></i> Filtros Rápidos
      </h5>
      <div class="sidebar-list">
        <a href="table.php?status=1" class="sidebar-link">
          <i class="fa fa-book"></i> Leyendo
        </a>
        <a href="table.php?status=2" class="sidebar-link">
          <i class="fa fa-check-circle"></i> Completados
        </a>
        <a href="table.php?status=5" class="sidebar-link">
          <i class="fa fa-bookmark"></i> Por Leer
        </a>
        <a href="table.php?status=3" class="sidebar-link">
          <i class="fa fa-pause-circle"></i> En Pausa
        </a>
        <a href="table.php?status=4" class="sidebar-link">
          <i class="fa fa-times-circle"></i> Abandonados
        </a>
      </div>
    </div>

    <!-- Sección de Idiomas -->
    <div class="sidebar-section">
      <h5 class="sidebar-section-title">
        <i class="fa fa-globe"></i> Idiomas
      </h5>
      <div class="sidebar-list" id="sidebarLanguages">
        <a href="home.php?idioma=ES" class="sidebar-link">
          <i class="fa fa-flag"></i> Español
        </a>
        <a href="home.php?idioma=EN" class="sidebar-link">
          <i class="fa fa-flag"></i> Inglés
        </a>
      </div>
    </div>

    <!-- Sección de Géneros -->
    <div class="sidebar-section">
      <h5 class="sidebar-section-title">
        <i class="fa fa-tags"></i> Géneros
      </h5>
      <div class="sidebar-list" id="sidebarGenres">
        <!-- Los géneros se cargarán dinámicamente -->
        <div class="loading-text">Cargando géneros...</div>
      </div>
    </div>
  </div>
</div>

<br><br><br>

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Theme Switcher CSS -->
<link rel="stylesheet" href="../../public/css/themes.css">

<!-- Modal para escanear ISBN -->
<div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanModalLabel">Escanear ISBN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="scanForm" action="../controller/scannerController.php" method="GET">
          <div class="form-group">
            <label for="isbnInput">Introduce el ISBN del libro:</label>
            <input type="text" class="form-control" id="isbnInput" name="ISBN" 
                   placeholder="Ej: 9788408180401" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="scanForm" class="btn btn-primary">Buscar</button>
      </div>
    </div>
  </div>
</div>

<!-- Script para manejar el foco en el modal -->
<script>
$(document).ready(function() {
  $('#scanModal').on('shown.bs.modal', function () {
    $('#isbnInput').focus();
  });
});
</script>

<!-- Theme Switcher JS -->
<script src="../../public/js/theme-switcher.js"></script>

<!-- Sidebar JS -->
<script src="../../public/js/sidebar.js"></script>