<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../../public/img/ico1.ico">
    <link rel="stylesheet" href="../../public/css/libro.css">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
                        
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<div class="v-card">
	<!-- Header -->
	<header>
		<ul class="list-inline">
			<li class="active"><a href="home.php" >Home</a></li>
		</ul>
		<h3>Añade un libro</h3>
	</header>
	<!-- tab Content -->
	<div class="tab-content">
		<!-- Tab Pane - Home -->
		<div class="tab-pane fade active in" id="home">
        <form action="../controller/anadirController.php" method="POST">
		      		<div class="form-group">
		      			<input type="text" name="titulo" placeholder="Titulo" class="form-control"  required>
		      		</div>

		            <div class="form-group">
		              <input type="text" name="autor" class="form-control" placeholder="Autor" required>
		            </div>

                    <div class="form-group">
		              <input type="text" name="saga" class="form-control" placeholder="Saga" required>
		            </div>

                    <div class="form-group">
		              <input type="text" name="numSaga" class="form-control" placeholder="Numero del libro dentro de la saga" required>
		            </div>

                    <div class="form-group">
						<?php
							include '../../config/conexion.php'; 
							$sql = "select * from genero order by Genero ASC";
							$result = $db->query($sql);  
							
							echo "<select name='genero' class='form-control'>";
							while ($row = $result->fetch_assoc()) {
								$id = $row['Id_Genero'];
								$genero = $row['Genero']; 
								// Comprobar si el ID es 1 para establecerlo como seleccionado
								$selected = ($id == 1) ? 'selected' : '';
								echo '<option value="'.htmlspecialchars($id).'" '.$selected.'>'.htmlspecialchars($genero).'</option>';
							}
							echo "</select>";
						?>
		            </div>

					<div class="form-group">
		              <input  type="text" name="portada" class="form-control" placeholder="URL de imagen de la portada" required>
		            </div>

                    <div class="form-group">
		              <textarea style="height: 200px;" type="text" id="sinopsis" name="sinopsis" class="form-control" placeholder="Sinopsis" required></textarea>
		            </div>

		            <div class="form-group">
		            	<button type="submit" class="btn form-control btn-primary rounded submit px-3">Guardar</button>
		            </div>
	          </form>
		</div>
	</div>
</div>                                        
</body>
</html>