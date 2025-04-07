<?php

include 'Connessione.php';
session_start();

$nickname = $_REQUEST['nickname'];
$userPassword = $_REQUEST['password'];


$sql = "SELECT password FROM utenti WHERE nickname='$nickname'";
$result = $connessione->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verifica della password
    if (password_verify($userPassword, $row['password'])) {
       
        $_SESSION['nickname'] = $nickname;
        header("Location: ../index.php");
        
    } else {
        echo "<script>
        alert('Credenziali errate!');
        window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('Credenziali errate!');
        window.history.back();
        </script>";
}

$connessione->close();

?>
