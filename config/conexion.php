<?php      

    // Database configuration 
    $host;
    $bbdd;
    $user;
    $password;
    $charset;

    $host     = 'localhost';
    $bbdd     = 'mybooklist';
    $user     = 'root';
    $password = "";
    $charset  = 'utf8mb4';
    
    // Create database connection 
    $db = new mysqli($host, $user, $password, $bbdd); 
    $db->set_charset("utf8");  
    
    // Check connection 
    if ($db->connect_error) { 
        die("Connection failed: " . $db->connect_error); 
    }
?> 