<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixologyMate | Registrazione</title>
    <link rel="icon" type="image/png" href="immagini/Logo app schede.png">
    <link rel="stylesheet" href="style/Registrazione.css">
    <style>
        /* Stili aggiuntivi per l'errore */
        .password-error {
            color: #ff0000;
            font-size: 0.9em;
            margin-top: -10px;
            margin-bottom: 10px;
            display: none;
        }

        .password-error.show {
            display: block;
        }

        .error {
            border: 2px solid #ff0000 !important;
            background: #fff3f3;
        }

        .password-error::before {
            content: "❗ ";
        }
        
    </style>
</head>
<body>

    <div class="container">
        <div class="image-container">
            <img src="immagini/Logo app schede.png" alt="Logo MixologyMate">
            <div class="site-name">MixologyMate</div>
        </div>

        <h2>Registrazione</h2>
        <form action="ControlloRegistrazione.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nickname" placeholder="Nickname" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" class="password-field" required>
            <input type="password" name="password_confirm" placeholder="Conferma Password" class="password-field" required>
            <div class="password-error" id="password-error">Le password non coincidono</div>
            <label for="profile-image">Immagine del profilo:</label>
            <input type="file" id="profile-image" name="profile_image" accept="image/*">
            <input type="submit" value="Registrati">
        </form>

        <div class="register-link">
            <a href="Login.php">Hai già un account? Accedi</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.querySelector('input[name="password"]');
            const confirmPassword = document.querySelector('input[name="password_confirm"]');
            const errorMessage = document.getElementById('password-error');
            const passwordFields = document.querySelectorAll('.password-field');

            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    passwordFields.forEach(field => field.classList.add('error'));
                    errorMessage.classList.add('show');
                } else {
                    passwordFields.forEach(field => field.classList.remove('error'));
                    errorMessage.classList.remove('show');
                }
            }

            passwordFields.forEach(input => {
                input.addEventListener('input', validatePassword);
            });
        });
    </script>
    
</body>
</html>