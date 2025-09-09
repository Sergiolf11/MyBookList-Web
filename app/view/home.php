<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../public/img/ico1.ico">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../public/css/buscar.css">
    <script> 
        $(function(){
            $("#header").load("header.php"); 
        });
    </script>
</head>
<body class="bg-light" data-theme="dark">
<div id="header"></div>
<div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-8 col-lg-6">
            <!-- Section Heading-->
            <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
            
              <h3>Welcome to the Archive</h3>
              <?php
                    require_once('../controller/homeController.php');  
                    getCount();
                ?>  
              <div class="line"></div>
            </div>
          </div>
        </div>
        <div style="border-radius: 25px;border: 1px solid #4dd0e1;box-shadow: 0 0 0 1px #4dd0e1;" class="bg-white">
            <input id="buscar" style="display: inline;width: 88%;border:none;border-radius: 30px;" class="form-control" type="text" placeholder="Search" aria-label="Search" onkeydown="if (event.keyCode == 13){ document.getElementById('botonBusqueda').click(); return false;}"/>
            <div class="btn" id="botonBusqueda" style="float: right;"  onclick="busqueda()">
                <a class="fa fa-search"></a>
            </div> 
      
        </div><br>
        
          <!-- Libros-->
            <?php
                require_once('../controller/homeController.php');  
                getAll();
            ?>
        
      </div>
<script type="text/javascript">
</script>

<script>
// Sincronizar el tema entre body y navbar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar tema guardado al body
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-theme', savedTheme);
    
    // Sincronizar con navbar cuando se carga
    setTimeout(() => {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            navbar.setAttribute('data-theme', savedTheme);
        }
    }, 100);
});

// Escuchar cambios de tema para sincronizar
window.addEventListener('themeChanged', function(event) {
    const newTheme = event.detail.theme;
    document.body.setAttribute('data-theme', newTheme);
    
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.setAttribute('data-theme', newTheme);
    }
});
</script>
</body>
<script>
    function busqueda() {
        var busqueda=document.getElementById("buscar");
        window.location="?search="+busqueda.value;
    }
</script>
</html>