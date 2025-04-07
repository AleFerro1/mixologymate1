<!DOCTYPE html>
<?php

session_start();
if (isset($_SESSION)) {
    session_unset();    // Rimuove tutte le variabili di sessione
    session_destroy();  // Distrugge la sessione
}
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixologyMate | Accedi</title>
    <link rel="icon" type="image/png" href="immagini/Logo app schede.png">
    <link rel="stylesheet" href="style/styleLogin.css"> 
</head>
<body>

    <div class="container">
        <div class="image-container">
            <img src="immagini/Logo app schede.png" alt="Logo MixologyMate">
            <div class="site-name">MixologyMate</div>
        </div>

        <h2>Accedi</h2>
        <form action="VerificaLogin.php" method="POST">
            <input type="text" name="nickname" placeholder="Nickname" required>
            <input type="password" name="password" placeholder="Password" required>
            <label>
                <input type="checkbox" name="remember_me"> Ricordami
            </label>
            <input type="submit" value="Login">
        </form>

        <div class="register-link">
            <a href="Registrazione.php">Non hai un account? Registrati</a>
        </div>
    </div>

</body>
</html>
