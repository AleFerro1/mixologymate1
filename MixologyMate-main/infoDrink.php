<?php
include 'Connessione.php';

// Verifica presenza parametro ID
if(!isset($_GET['idDrink']) || !is_numeric($_GET['idDrink'])) {
    header("Location: error.php");
    exit();
}

$drink_id = intval($_GET['idDrink']);

//uso group concat per avere tutti gli ingredienti in un solo campo
//prendo tutte le informazione del drink + gli ingredienti e le quantità
//raggruppo per idDrink visto che devo fare un AVG e un COUNT
$sql = "
    SELECT 
        d.*, 
        ing.ingredienti,
        AVG(r.numeroStelle) AS rating_medio,
        COUNT(r.idRecensione) AS totale_recensioni
    FROM 
        drink d
    LEFT JOIN (
        SELECT 
            ai.idDrink,
            GROUP_CONCAT(CONCAT(i.nomeIngrediente, ' (', ai.Quantità, ')') SEPARATOR ', ') AS ingredienti 
        FROM 
            associazioneIngredienti ai
        INNER JOIN 
            ingredienti i ON ai.idIngrediente = i.idIngrediente
        GROUP BY 
            ai.idDrink
    ) ing ON d.idDrink = ing.idDrink
    LEFT JOIN 
        recensioni r ON d.idDrink = r.idDrink
    WHERE 
        d.idDrink = ?
    GROUP BY d.idDrink";

$stmt = $connessione->prepare($sql);
$stmt->bind_param('i', $drink_id);
$stmt->execute();
$result = $stmt->get_result();
$drink = $result->fetch_assoc();

if(!$drink) {
    die("Drink non trovato!");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($drink['nomeDrink']) ?> - MixologyMate</title>
    <link rel="stylesheet" href="mixologySyles.css">
    <style>
        body{
            background: linear-gradient(135deg, rgba(144, 255, 248, 0.5), rgba(4, 0, 115, 0.8), rgba(11, 133, 117, 0.5));
        }
        .drink-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .drink-header {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .drink-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .drink-info {
            padding: 1rem;
        }

        .drink-title {
            font-size: 2.5rem;
            margin: 0 0 1rem 0;
            color: #2d2d2d;
        }

        .meta-section {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .rating-badge {
            background: #ffb700;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            border-color:rgb(238, 171, 0);
            font-weight: bold;
            cursor: pointer;
        }

        .ingredienti-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .ingrediente-item {
            display: grid;
            grid-template-columns: 1fr 100px;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .back-button {
            background: #007bff;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
        }

        .back-button:hover {
            background: #0056b3;
        }
        .recensione-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .recensione-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .star-rating {
            padding-left: 1px;
            color: #ffb700;
            font-size: 1.4em;
            margin-right: 10px;
            margin-bottom: 3px;
        }

        
        .buttonRecensioni{
            margin-top: 15px;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            background-color:  white;
            color: black;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            align-self: center;
        }
        .buttonRecensioni:hover{
            background-color:rgb(233, 233, 233);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .immagine-profilo {
            width: 40px; /* o la dimensione desiderata */
            height: 40px; /* o la dimensione desiderata */
            border-radius: 50%; /* per rendere l'immagine circolare */
            margin-right: 10px;
        }
        .nicknameRecensione{
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php include 'Connessione.php'; ?>

    <div class="container">
        <a href="javascript:history.back()" class="back-button">← Torna indietro</a>
        
        <div class="drink-container">
            <div class="drink-header">
                <img src="../mixologymate/<?= htmlspecialchars($drink['immagine']) ?>" 
                     alt="<?= htmlspecialchars($drink['nomeDrink']) ?>" 
                     class="drink-image">
                
                <div class="drink-info">
                    <h1 class="drink-title"><?= htmlspecialchars($drink['nomeDrink']) ?></h1>
                    
                    <div class="meta-section">
                        <div class="meta-item">
                            <span>⭐</span>
                            <div>
                                <h4>Valutazione</h4>
                                <a href="recensione.php?idDrink=<?= $drink_id ?>"><button class="rating-badge">
                                    <?= number_format($drink['rating_medio'], 1) ?>/5 
                                    (<?= $drink['totale_recensioni'] ?>
                                    
                                     recensioni)
                                </button></a>
                            </div>
                        </div>
                        
                        <div class="meta-item">
                            <span>👨🍳</span>
                            <div>
                                <h4>Creato da</h4>
                                <p><?= htmlspecialchars($drink['creatore']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="ingredienti-section">
                        <h3>Ingredienti</h3>
                        <?php 
                        $ingredienti = explode(', ', $drink['ingredienti']); //trasformo la string in un array che si divide per ogni , della stringa
                        
                        foreach($ingredienti as $ingrediente): 
                        ?>
                            <div class="ingrediente-item">
                                <span><?= htmlspecialchars(explode(' (', $ingrediente)[0]) ?></span> <!-- qui divide la stringa in ingrediente e quantità-->
                                <span><?= explode(')', explode(' (', $ingrediente)[1])[0] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h3>Descrizione</h3>
                    <p><?= htmlspecialchars($drink['descrizione'] ?? 'Nessuna descrizione disponibile') ?></p>
                </div>
            </div>

            <!-- Sezione aggiuntiva per recensioni -->
            <div class="recensioni-section">
    <h2>Recensioni</h2>
    <?php //prende le ultima 3 recensioni 
    $sql_recensioni = "
    SELECT r.*, u.nickname, u.immagine
    FROM recensioni r
    JOIN utenti u ON r.nicknameCreatore = u.nickname
    WHERE r.idDrink = ?
    ORDER BY r.dataCreazione DESC
    LIMIT 3";
$stmt = $connessione->prepare($sql_recensioni);
$stmt->bind_param('i', $drink_id);
$stmt->execute();
$recensioni = $stmt->get_result();

    
    if($recensioni->num_rows > 0):
        while($recensione = $recensioni->fetch_assoc()):
    ?>
        <div class="recensione-card" onclick="location.href='recensione.php?idDrink=<?= $drink_id ?>'">
            <div class="recensione-header">
                <div>
                    <img src="../mixologymate/<?= htmlspecialchars($recensione['immagine']) ?>"  class="immagine-profilo">
                    <span class="nicknameRecensione"><?= htmlspecialchars($recensione['nicknameCreatore']) ?></span>
                </div>
                
                <div class="star-rating">
                    <?php
                    $stelle_piene = $recensione['numeroStelle'];
                    for ($i = 1; $i <= 5; $i++) {
                        /*if($i<=$stelle_piene)
                            echo '★';
                        else
                            echo '☆';*/
                        echo $i <= $stelle_piene ? '★' : '☆'; 
                    }
                    ?>
                </div>
                
            </div>
            <p><strong><?= htmlspecialchars($recensione['titolo']) ?></strong></p>
            <p><?= htmlspecialchars($recensione['descrizione']) ?>...</p>
        </div>
    <?php
        endwhile;
    else:
    ?>
        <p>Ancora nessuna recensione. <a href="recensione.php?idDrink=<?= $drink_id ?>">Scrivi la prima!</a></p>
    <?php endif; ?>
    
    <a href="recensione.php?idDrink=<?= $drink_id ?>" class="btn-primary"><button class="buttonRecensioni">Vedi tutte le recensioni</button></a>
            </div>
        </div>
    </div>
</body>
</html>