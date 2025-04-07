<?php
    session_start();
    if (isset($_SESSION['nickname'])){
        include 'MixologyMate-main/Connessione.php';
        $nickname = $_SESSION['nickname'];
        $sql = "SELECT * FROM utenti WHERE nickname = '$nickname'";
        $result = $connessione->query($sql);
        $row = $result->fetch_assoc();    
    }  
?> 
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixologyMate</title>
    <link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
    <link rel="stylesheet" href="mixologySyles.css">
    <style>
        .menu-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 50px 0;
        }
        
        .menu-button {
            padding: 15px 30px;
            font-size: 18px;
            background-color:  white;
            color: black;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        
        .menu-button:hover {
            background-color:rgb(233, 233, 233);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .dropdown-content a:hover {
            background-color: #f1f1f1;
            border-radius: 8px;
        }
        
        .show {
            display: block;
        }
        
        /* Stili esistenti */
        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .search-section-p {
            
            text-align: center; /* Centra il testo orizzontalmente */
            font-size: 60px; /* Imposta una dimensione del font maggiore */
            font-weight: bolder;
            margin-top: 100px; /* Distanza dalla parte superiore della pagina */
            margin-bottom: 20px; /* Spazio tra il testo e la barra di ricerca */
        }
        .reviews-section .scroll-arrows {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 2;
            user-select: none;
            transition: all 0.3s;
        }
        .reviews-section .scroll-left {
            left: 15px;
        }

        .reviews-section .scroll-right {
            right: 15px;
        }


    .scroll-arrows.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-50%) scale(0.8);
    }
    
    .scroll-arrows:hover {
        background: #f1f1f1;
        transform: translateY(-50%) scale(1.1);
    }

    .scroll-left {
        left: 15px;
    }

    .scroll-right {
        right: 15px;
    }
    .section-title {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 30px;
    }

    .reviews-scroller {
        display: flex;
        overflow-x: hidden;
        gap: 25px;
        padding: 20px 0;
        scroll-behavior: smooth;
        position: relative;
    }

    .review-card {
        flex: 0 0 300px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        text-decoration: none;
    }

    .drink-thumbnail img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-bottom: 3px solid #eee;
    }
    .drink-thumbnail:hover img{
        opacity: 0.93;
        transition: opacity 0.3s
    }

    .review-content {
        padding: 15px;
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }

    .user-name {
        font-weight: 600;
        color: #333;
    }

    .rating {
        color: #ffb700;
        font-size: 18px;
        margin-bottom: 10px;
    }

    .review-text {
        color: #666;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .review-date {
        color: #999;
        font-size: 12px;
        display: block;
        text-align: right;
    }

    .no-reviews {
        text-align: center;
        color: #666;
        width: 100%;
        padding: 20px;
    }

    
    
    .scroll-arrows-drinks {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    z-index: 2;
    user-select: none;
    transition: all 0.3s;
}

.scroll-left-drinks {
    left: 15px;
}

.scroll-right-drinks {
    right: 15px;
}
.reviews-section, 
.top-drinks-section {
    position: relative;
    padding: 40px 60px;
}
.drinks-scroller, 
.reviews-scroller {
    position: relative;
    z-index: 1;
}
scroll-left-drinks {
    left: 15px;
}

.scroll-right-drinks {
    right: 15px;
}

.scroll-arrows-drinks {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    
}
.scroll-arrows-drinks.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-50%) scale(0.8);
    }
    
.scroll-arrows-drinks:hover {
    background: #f1f1f1;
    transform: translateY(-50%) scale(1.1);
}
    </style>
</head>
<body>
    
    <div class="container">
        <!-- Barra sopra nella home-->
        <div class="barraSopra">
            <a href="index.php"><img src="immagini/logoHome.png" alt="Logo" class="logoHome"></a>
            <div class="menu-container">
                <div class="menu-item">
                    <button class="menu-button">Novità</button>
                </div>
            
                <div class="menu-item">
                    <button class="menu-button" onclick="toggleDropdown('drink-dropdown')">Lista Drink</button>
                    <div id="drink-dropdown" class="dropdown-content">
                        <a href="MixologyMate-Main/listaDrink.php">Alcolici</a>
                        <a href="MixologyMate-Main/listaDrink.php">Analcolici</a>
                    </div>
                </div>
            
            <div class="menu-item">
                <button class="menu-button">Altro</button>
            </div>
        </div>
            <?php 
            if (isset($_SESSION['nickname'])) {
                $pathImmagine = $row['immagine'];
                
                if (!empty($pathImmagine)) {
                    /*if (strpos($db_image_path, 'C:\\xampp\\htdocs') === 0) {
                        $immagine_profilo = '/mixologymate/uploads/profiles/' . basename($db_image_path);
                    } else {*/
                        
                        $immagine_profilo = $pathImmagine;
                        
                    }
                else {
                    $immagine_profilo = 'uploads/profiles/profile_67e542f5a5828.png';
                    
                }
                
                echo '<a href="MixologyMate-main/profilo.php"><img class="immagineProfilo" src="/mixologymate/' . htmlspecialchars($immagine_profilo) . '"></a>';
            } else {
                echo '<a href="MixologyMate-main/Login.php"><button class="bottoneAccedi">Accedi</button></a>';
            }
            ?>
        </div>

        <!-- Barra di ricerca letsgosky letsgo -->
        <p class="search-section-p">Che ingredienti hai?</p>
        <div class="search-section">
            <div class="search-container">
                <input type="text" id="search-input" placeholder="Cerca ingredienti...">
                <div id="suggestions-container"></div>
            </div>
            <div id="selected-ingredients"></div>
            <button id="search-button">Cerca cocktail</button>
        </div>
        <div class="MMChoice">
            <a href="MixologyMate-main/MMs_Choice.php"><img src="mixologymate/uploads/mmChoice/MM_choice2.png" alt="Descrizione immagine"></a>
        </div>
        <?php
        include 'MixologyMate-main/Connessione.php';
    // Query per le ultime recensioni
    $sql = "SELECT R.*, D.nomeDrink, D.immagine, U.immagine AS userImage 
                    FROM recensioni R 
                    JOIN drink D ON R.idDrink = D.idDrink 
                    JOIN utenti U ON R.nicknameCreatore = U.nickname 
                    ORDER BY R.dataCreazione DESC 
                    LIMIT 10";
    $result = $connessione->query($sql);
    ?>

    <div class="reviews-section">
        <h2 class="section-title">Le ultime recensioni dalla community</h2>
        <div class="scroll-arrows scroll-left hidden" onclick="scrollReviews(-325)">❮</div>
        <div class="scroll-arrows scroll-right hidden" onclick="scrollReviews(325)">❯</div>
    
        <div class="reviews-scroller" id="reviewsScroller" onscroll="checkScroll()">
            <?php if($result->num_rows > 0): ?>
                <?php while($recensione = $result->fetch_assoc()): ?>
                    <a href="MixologyMate-main/infoDrink.php?idDrink=<?=$recensione['idDrink']?>" class="review-card"><div class="review-card">
                        <div class="drink-thumbnail">
                            <img src="../mixologymate/<?= $recensione['immagine'] ?>" 
                                alt="<?= $recensione['nomeDrink'] ?>">
                        </div>
                        
                        <div class="review-content">
                            <div class="user-info">
                                <img src="../mixologymate/<?= $recensione['userImage'] ?>" 
                                    class="user-avatar" 
                                    alt="Avatar utente">
                                <span class="user-name"><?= $recensione['nicknameCreatore'] ?></span>
                            </div>
                            <div class="rating">
                                <?= str_repeat('★', $recensione['numeroStelle']) ?>
                            </div>
                            <p class="review-text"><?= $recensione['descrizione'] ?></p>
                            <span class="review-date"><?= $recensione['dataCreazione'] ?></span>
                        </div>
                    </div></a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-reviews">Nessuna recensione disponibile</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    $sql = "SELECT D.*, 
                    AVG(R.numeroStelle) as valutazione_media,
                    COUNT(R.idRecensione) as numero_recensioni
                FROM drink D
                LEFT JOIN recensioni R ON D.idDrink = R.idDrink
                GROUP BY D.idDrink
                ORDER BY valutazione_media DESC, numero_recensioni DESC
                LIMIT 10";
    $result = $connessione->query($sql);
    ?>

<div class="top-drinks-section">
    <h2 class="section-title">Drink più Votati</h2>
    <div class="scroll-arrows-drinks scroll-left-drinks hidden" onclick="scrollDrinks(-325)">❮</div>
    <div class="scroll-arrows-drinks scroll-right-drinks hidden" onclick="scrollDrinks(325)">❯</div>
        <div class="drinks-scroller" id="drinksScroller" onscroll="checkDrinksScroll()">
            <?php if($result->num_rows > 0): ?>
                <?php while($drink = $result->fetch_assoc()): ?>
                    <a href="MixologyMate-main/infoDrink.php?idDrink=<?= $drink['idDrink'] ?>" class="drink-card">
                        <div class="drink-thumbnail">
                            <img src="../mixologymate/<?= $drink['immagine'] ?>" 
                                alt="<?= $drink['nomeDrink'] ?>">
                        </div>
                        <div class="drink-content">
                            <h3 class="drink-name"><?= htmlspecialchars($drink['nomeDrink']) ?></h3>
                            <div class="rating">
                                <?php if($drink['valutazione_media']): ?>
                                    <?= str_repeat('★', round($drink['valutazione_media'])) ?>
                                    <?= number_format($drink['valutazione_media'], 1) ?>
                                    <span class="reviews-count">(<?= $drink['numero_recensioni'] ?>)</span>
                                <?php else: ?>
                                    <span>Nessuna valutazione</span>
                                <?php endif; ?>
                            </div>
                            <p class="drink-description"><?= htmlspecialchars($drink['descrizione']) ?></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-drinks">Nessun drink disponibile</p>
            <?php endif; ?>
        </div>
    </div>

        <p class="socialsAboutUs">About us</p>
        <div class="socialsDiv">
            <a href="https://www.instagram.com/mixologymate?igsh=amdqMG1lcjBzZnB0"><img class="socials" src="mixologymate/uploads/immaginiSocials/instagram2.png"></a>
            <img src="mixologymate/uploads/immaginiSocials/Barra-vertical.png">
            <img class="socials" src="mixologymate/uploads/immaginiSocials/twitter.png">
            <img src="mixologymate/uploads/immaginiSocials/Barra-vertical.png">
            <img class="socials" src="mixologymate/uploads/immaginiSocials/tiktok.png">
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
