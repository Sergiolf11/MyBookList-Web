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
    <link rel="stylesheet" href="../../public/css/buscar.css">
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
        <div class="col-12 col-sm-8 col-lg-16">
            <!-- Section Heading-->
            <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                <h3>Welcome to your little corner of the Archive</h3>
                <p>Look for the book you want, if it is not there, place it.</p>
                <div class="line"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="page-people-directory">
            <div class="row">
                <div class="col-md-12">
                    <div class="list-group contact-group ">
                        <nav class="navbar bg-light">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link" href="?status=0">All Books</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="?status=1">Currently Reading</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="?status=2">Completed</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="?status=3">On Hold</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="?status=4">Dropped</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="?status=5">Plan to Read</a>
                                </li>  
                            </ul>
                        </nav>
                        <br>
                        <div class="row">
                            <?php
                                require_once('../controller/homeController.php');
                                getAllFromUser();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript">
</script>
</body>
</html>