<?php
session_start();
include 'Connessione.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = $_SESSION['nickname'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verifica match password
    if($newPassword !== $confirmPassword) {
        //$_SESSION['password_error'] = "Le password non coincidono";
        header("Location: profilo.php#security");
        exit;
    }

    // Recupera password corrente
    $sql = "SELECT password FROM utenti WHERE nickname = ?";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica password corrente devo fa sta cosa perchè è hashata  :(
    if(!password_verify($currentPassword, $user['password'])) {
        //$_SESSION['password_error'] = "Password corrente errata";
        header("Location: profiloImpostazioni.php#security");
        exit;
    }

    // Aggiorna password
    $nuovoHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE utenti SET password = ? WHERE nickname = ?";
    $updateStmt = $connessione->prepare($sql);
    $updateStmt->bind_param('ss', $nuovoHash, $nickname);

    $updateStmt->execute();
        

    header("Location: profilo.php#security");
    exit;
}
