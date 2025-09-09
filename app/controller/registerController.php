<?php 
session_start();
    $username = $_POST['username'] ?? '';  
    $password = $_POST['password'] ?? '';  
    $email = $_POST['email'] ?? '';  
    // Include the database config file 
    include '../../config/conexion.php'; 

    if ($username === '' || $password === '' || $email === '') {
        header("Location: ../view/register.php?denegado=1");
        exit;
    }

    $pwd = password_hash($password, PASSWORD_DEFAULT);

    // Verificar duplicados con prepared statement
    $stmt = $db->prepare("SELECT 1 FROM usuario WHERE Email = ? OR Username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../view/register.php?denegado=1");
        exit;
    }

    // Insertar usuario
    $insert = $db->prepare("INSERT INTO usuario (Username, Password, Email) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $username, $pwd, $email);

    if ($insert->execute()) {
        header("Location: ../view/login.php?registro=ok");
        exit;
    } else {
        header("Location: ../view/register.php?error=1");
        exit;
    }
?>