<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixologyMate | Conferma Registrazione</title>
    <link rel="icon" type="image/png" href="immagini/Logo app schede.png">
    <link rel="stylesheet" href="style/ControlloRegistrazione.css">
</head>
<body>

<?php
include 'Connessione.php';

$nickname = $_POST['nickname'];
$password = $_POST['password'];
$email = $_POST['email'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "SELECT * FROM utenti WHERE nickname = '$nickname'";
$result = $connessione->query($sql);
    
    if($result->num_rows>0){ //solo se è presente solo una riga
        echo "<script>
            window.history.back();
            alert('Username già in uso');
            
            </script>";
            exit;
    }
    else{
        
        $sql = "INSERT INTO utenti (nickname,password,email) VALUES ('$nickname','$hashed_password','$email')";
        if($connessione->query($sql) === TRUE) {
            header('location: ../index.php');
        } else {
            header('location:Registrazione.php');
            exit;
        }
    }

?>