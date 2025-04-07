<?php
    session_start();
    include 'Connessione.php';
    $nickname = $_SESSION['nickname'];
    
    $sql = "SELECT * FROM utenti WHERE nickname = '$nickname'";
    $result = $connessione->query($sql);
    $row = $result->fetch_assoc();
    
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Profilo</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(135deg, rgba(144, 255, 248, 0.5), rgba(4, 0, 115, 0.8), rgba(11, 133, 117, 0.5));
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            min-height: 100vh;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-content {
            display: flex;
            gap: 30px;
        }

        .sidebar {
            width: 250px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .main-content {
            flex: 1;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin: 15px 0;
        }

        .nav-item a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav-item a:hover {
            color: #1877f2;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }

        .btn {
            background: #1877f2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background: #166fe5;
        }

        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
        }
        .logout-btn {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .logout-link {
            color: #e74c3c !important;
            font-weight: 600 !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-link:hover {
            color: #c0392b !important;
        }

        .logout-icon {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }
        .file-input-container {
    position: relative;
    text-align: center;
    margin-top: 15px;
}

.custom-file-input {
    display: inline-flex;
    align-items: center;
    padding: 12px 25px;
    background: #1877f2;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    border: 2px solid transparent;
}

.custom-file-input:hover {
    background: #166fe5;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.custom-file-input:active {
    transform: translateY(0);
}

.upload-icon {
    margin-right: 10px;
}

#profilePhoto {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-name {
    display: block;
    margin-top: 10px;
    color: #666;
    font-size: 0.9em;
}
.bio-input {
    width: 100%;
    height: 120px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    resize: vertical;
    font-family: 'Arial', sans-serif;
    font-size: 14px;
    line-height: 1.5;
    transition: border-color 0.3s ease;
}

.bio-input:focus {
    border-color: #1877f2;
    outline: none;
    box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.1);
}

.char-counter {
    text-align: right;
    font-size: 0.8em;
    color: #666;
    margin-top: 5px;
}
.form-text {
    display: block;
    margin-top: 5px;
    font-size: 0.8em;
    color: #666;
}

.error-message {
    color: #dc3545;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 5px;
    background: #f8d7da;
    display: none;
}
    </style>
</head>
<body>
    <?php

    ?>
    <div class="container">
        <div class="profile-header">
            <h1>Gestione Profilo</h1>
        </div>

        <div class="profile-content">
            <aside class="sidebar">
                <nav>
                    <ul class="nav-menu">
                        <li class="nav-item"><a href="#info">Informazioni Personali</a></li>
                        <li class="nav-item"><a href="#security">Sicurezza</a></li>
                        
                        
                        <a href="Logout.php"><li>Logout</li></a>
                    </ul>
                </nav>
            </aside>

            <main class="main-content">
                
                <section id="info">
                    <h2>Informazioni Personali</h2>
                    <form action="modificaProfilo.php" method="POST" id="profileForm" >
                        <div class="file-input-container">
                            <img src="<?php echo '../mixologymate/'.$row['immagine'] ?>" class="profile-pic" id="profilePreview">
                            <input type="file" id="profilePhoto" name="profilePhoto"  onchange="previewImage(event)">
                            <label for="profilePhoto" class="custom-file-input">
                            <!--<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line> 
                            </svg>-->
                            Scegli immagine
                        </label>
                        <span class="file-name" id="fileName">Nessun file selezionato</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="nickname">Nickname</label>
                            <input type="text" 
                                id="nickname" 
                                name="nickname" 
                                value="<?php echo htmlspecialchars($row['nickname']); ?>" 
                                required
                                style="border: 1px solid #ddd; padding: 8px;">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo htmlspecialchars($row['email']); ?>" 
                                required
                                style="border: 1px solid #ddd; padding: 8px;">
                        </div>
                        <div class="form-group">
                            <label for="descrizione">Descrizione</label>
                            <textarea 
                                id="descrizione" 
                                name="descrizione" 
                                class="bio-input" 
                                maxlength="500"
                                placeholder="Scrivi qualcosa su di te..."><?php echo htmlspecialchars($row['descrizione'] ?? ''); ?></textarea>
                            <div class="char-counter"><span id="charCount">0</span>/500</div>
                        </div>
                        
                        <button type="submit" class="btn">Salva modifiche</button>
                    </form>
                </section>

                <section id="security" style="display: none;">
                    <h2>Sicurezza</h2>
                    <form id="securityForm" action="cambia_password.php" method="POST">
                        <div class="form-group">
                            <label for="currentPassword">Password attuale</label>
                            <input type="password" id="currentPassword" name="currentPassword" required>
                        </div>

                        <div class="form-group">
                            <label for="newPassword">Nuova password</label>
                            <input type="password" id="newPassword" name="newPassword" required 
                                minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$">
                            <small class="form-text">Minimo 8 caratteri, almeno una lettera e un numero</small>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Conferma password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                        </div>

                        <button type="submit" class="btn">Cambia password</button>
                    </form>
                </section>

                
            </main>
        </div>
    </div>

    <script>
        // Gestione navigazione tra sezioni
        document.querySelectorAll('.nav-item a').forEach(link => {
            link.addEventListener('click', (e) => { //qua aggiungo per ogni nav il click
                
                const targetId = link.getAttribute('href'); 
                document.querySelectorAll('main section').forEach(section => {
                    section.style.display = 'none'; //qua ce metto questo sennò succede ncasino
                });
                document.querySelector(targetId).style.display = 'block';// qua mostra la sezione
            });
        });

        // Anteprima immagine profilo qua ho usato deepseek javascript è la feccia del mondo
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('profilePreview');
            
            reader.onload = function() {
                preview.src = reader.result;
            };
            
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                preview.src = "https://via.placeholder.com/150";
            }
            const fileName = document.getElementById('fileName');
            if(event.target.files[0]) {
                fileName.textContent = event.target.files[0].name;
                fileName.style.color = '#333';
            } else {
                fileName.textContent = 'Nessun file selezionato';
                fileName.style.color = '#666';
            }
        }

        // Gestione submit form
        function handleSubmit(event) {
            alert('Modifiche salvate con successo!');
        }

        
        //document.querySelector('#info').style.display = 'block';

        //modifica password
        document.getElementById('securityForm').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
    
    if(newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Le password non coincidono!');
        return false;
    }
    
    if(newPassword.length < 8) {
        e.preventDefault();
        alert('La password deve essere lunga almeno 8 caratteri');
        return false;
    }
    
    return true;
});
    </script>
</body>
</html>