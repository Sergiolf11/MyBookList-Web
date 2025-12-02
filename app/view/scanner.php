<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Escanear Libro - MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../public/img/ico1.ico">
    <link rel="stylesheet" href="../../public/css/libro.css">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .book-cover {
            max-width: 200px;
            margin: 0 auto 20px;
            display: block;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .book-info {
            margin-bottom: 20px;
        }
        .book-info dt {
            font-weight: bold;
            margin-top: 10px;
        }
        .book-info dd {
            margin-bottom: 10px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="v-card">
    <!-- Header -->
    <header>
        <ul class="list-inline">
            <li><a href="home.php"><i class="fa fa-arrow-left"></i> Volver</a></li>
            <li class="active">Escanear Libro</li>
        </ul>
    </header>
    
    <!-- Contenido -->
    <div class="tab-content">
        <div class="tab-pane fade active in" id="home">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="text-center">
                <h3>Escanear Código de Barras</h3>
                <p>Escanea el código de barras del libro o introduce el ISBN manualmente:</p>
                
                <form action="../controller/scannerController.php" method="GET" class="form-inline" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <input type="text" name="ISBN" class="form-control" placeholder="Introduce el ISBN" 
                               value="<?php echo htmlspecialchars($_GET['ISBN'] ?? ''); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </form>
                
                <?php if (isset($_SESSION['libro_temporal'])): 
                    $libro = $_SESSION['libro_temporal'];
                    unset($_SESSION['libro_temporal']);
                ?>
                    <div class="book-details">
                        <h4>Información del Libro Encontrada</h4>
                        <p><small class="text-muted">Fuente: <?php echo htmlspecialchars($libro['fuente']); ?></small></p>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo htmlspecialchars($libro['portada']); ?>" 
                                     alt="Portada del libro" class="book-cover img-responsive">
                            </div>
                            <div class="col-md-8">
                                <form action="../controller/anadirController.php" method="POST">
                                    <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($libro['isbn']); ?>">
                                    
                                    <div class="form-group">
                                        <label for="titulo">Título:</label>
                                        <input type="text" id="titulo" name="titulo" class="form-control" 
                                               value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="autor">Autor:</label>
                                        <input type="text" id="autor" name="autor" class="form-control" 
                                               value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="editorial">Editorial:</label>
                                        <input type="text" id="editorial" name="editorial" class="form-control" 
                                               value="<?php echo htmlspecialchars($libro['editorial']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="resumen">Resumen:</label>
                                        <textarea id="resumen" name="resumen" class="form-control" rows="4"><?php 
                                            echo htmlspecialchars($libro['resumen']); 
                                        ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="portada">URL de la portada:</label>
                                        <input type="url" id="portada" name="portada" class="form-control" 
                                               value="<?php echo htmlspecialchars($libro['portada']); ?>">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Guardar Libro
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['ISBN']) && !isset($libro)): ?>
                    <div class="alert alert-info">
                        <p>No se encontró información para el ISBN proporcionado. Por favor, introduce los datos manualmente.</p>
                        <form action="../controller/anadirController.php" method="POST" class="form-horizontal">
                            <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($_GET['ISBN']); ?>">
                            
                            <div class="form-group">
                                <label for="titulo" class="col-sm-2 control-label">Título:</label>
                                <div class="col-sm-10">
                                    <input type="text" id="titulo" name="titulo" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="autor" class="col-sm-2 control-label">Autor:</label>
                                <div class="col-sm-10">
                                    <input type="text" id="autor" name="autor" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="editorial" class="col-sm-2 control-label">Editorial:</label>
                                <div class="col-sm-10">
                                    <input type="text" id="editorial" name="editorial" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Guardar Libro
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script>
// Script para manejar el escaneo de códigos de barras
$(document).ready(function() {
    // Enfocar automáticamente el campo de búsqueda
    $('input[name="ISBN"]').focus();
    
    // Limpiar el mensaje de error después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
</body>
</html>