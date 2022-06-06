<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MyBookList</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../public/css/buscar.css">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script> 
$(function(){
  $("#header").load("header.php"); 
});
</script>
</head>
<body>

<div id="header"></div>

<div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-8 col-lg-6">
            <!-- Section Heading-->
            <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
              <h3>Welcome to the Archive</h3>
              <p>Look for the book you want, if it is not there, place it.</p>
              <div class="line"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- Libros-->
            <?php
                require_once('../controller/homeController.php');  
                getAll();
            ?>
        </div>
      </div>



<script type="text/javascript">

</script>
</body>
</html>