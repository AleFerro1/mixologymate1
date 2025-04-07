<?php
    session_start();
    
    if (isset($_SESSION['nickname'])){
        include 'Connessione.php';
        $nickname = $_SESSION['nickname'];
        $sql = "SELECT * FROM utenti WHERE nickname = '$nickname'";
        $result = $connessione->query($sql);
        $row = $result->fetch_assoc();    
        
        // Query per i drink creati
        $sql_drink = "SELECT * FROM drink WHERE creatore = '$nickname' ORDER BY dataCreazione DESC";
        $result_drink = $connessione->query($sql_drink);
    }
    
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Minimal</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body{
            background: linear-gradient(135deg, rgba(144, 255, 248, 0.5), rgba(4, 0, 115, 0.8), rgba(11, 133, 117, 0.5));
            margin: 0;
            padding: 0;
    
        }
       
        .container {
            max-width: 1242px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            position: relative;
            background-color: white;
            min-height: 100vh;
            border-radius: 5px;
        }

        /* Icona impostazioni */
        .settings-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #666;
            font-size: 1.5em;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .settings-icon:hover {
            color: #262626;
        }

        .profile-pic {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgb(44, 96, 255);
            padding: 3px;
            margin: 20px 0;
        }

        .bio-section {
            margin-bottom: 30px;
        }

        .bio-section h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #262626;
        }

        .bio-text {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            max-width: 400px;
            margin: 0 auto 15px;
        }

        .post-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
        }

        .post-item {
            width: 100%;
            aspect-ratio: 1/1;
            background: #eee;
            position: relative;
            cursor: pointer;
        }

        .post-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /* Icona + Centrale */
        .newDrink {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            background: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e1306c;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .newDrink::before {
            content: "+";
            font-size: 28px;
            font-weight: bold;
            color: #e1306c;
            margin-bottom: 3px;
        }
        .add-post-container {
            margin: 25px 0;
            text-align: center;
        }

        .add-post {
            display: inline-block;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #e1306c;
            background: white;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            position: relative;
            transition: transform 0.2s ease;
        }

        .add-post:hover {
            transform: scale(1.05);
        }

        .add-post::before {
            content: "+";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 28px;
            font-weight: bold;
            color: #e1306c;
        }
        .post-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 40px;
}

.post-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    aspect-ratio: 1/1;
}

.post-item:hover {
    transform: translateY(-5px);
}

.post-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.drink-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    padding: 15px;
    color: white;
    text-align: left;
}

.drink-overlay h3 {
    font-size: 1.2em;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.no-image {
    background: #f0f0f0;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.no-drinks {
    grid-column: 1 / -1;
    text-align: center;
    color: #666;
    padding: 40px 0;
}
    </style>
</head>
<body>
    <div class="container">
        
        <a href="profiloImpostazioni.php" class="settings-icon">
            <i class="fas fa-cog"></i>
        </a>
        
        
        <h1><?php echo $row['nickname']?></h1>
        
        
        <img src="<?php echo '../mixologymate/'.$row['immagine']; ?>" class="profile-pic" alt="Profile Picture">

        
        <div class="bio-section">
            <h1>@<?php echo $row['nickname']  ?></h1>
            <p class="bio-text"><?php echo $row['descrizione'] ?></p>
        </div>

        
        <div class="add-post-container">
            <a href="creazioneDrink.php"><div class="add-post"></div></a>
        </div>
        <div class="post-grid">
            <?php if($result_drink->num_rows > 0): ?>
                <?php while($drink = $result_drink->fetch_assoc()): ?>
                    <a href="infoDrink.php?idDrink=<?= $drink['idDrink'] ?>" class="post-item">
                        <?php if(!empty($drink['immagine'])): ?>
                            <img src="<?= htmlspecialchars($drink['immagine']) ?>">
                        <?php else: ?>
                            <div class="no-image">Nessuna immagine</div>
                        <?php endif; ?>
                        <div class="drink-overlay">
                            <h3><?= htmlspecialchars($drink['nomeDrink']) ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-drinks">Nessun drink creato ancora</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
