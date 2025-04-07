<?php
include 'Connessione.php';
//prendo gli ingredienti dalla barra di ricerca  e li trasformo in una stringa PHP
$ingredients = json_decode($_GET['ingredients']);


$placeholders = str_repeat('?,', count($ingredients) - 1) . '?';//qua prendo gli ingredienti e li metto in sta stringa per poi effettuare il controllo nella query
$num_ingredients = count($ingredients);
//La query interna trova i drink che hanno gli ingredienti cercati, la query esterna semplicemente da tutte le info del drink
$sql = "SELECT d.*, GROUP_CONCAT(DISTINCT i.nomeIngrediente SEPARATOR ', ') AS ingredienti
        FROM (
            SELECT d.idDrink
            FROM drink d
            INNER JOIN associazioneIngredienti ai ON d.idDrink = ai.idDrink
            INNER JOIN ingredienti i ON ai.idIngrediente = i.idIngrediente
            WHERE i.nomeIngrediente IN ($placeholders)
            GROUP BY d.idDrink
            HAVING COUNT(DISTINCT i.nomeIngrediente) = ?
        ) AS filtered_drinks
        INNER JOIN drink d ON filtered_drinks.idDrink = d.idDrink
        INNER JOIN associazioneIngredienti ai ON d.idDrink = ai.idDrink
        INNER JOIN ingredienti i ON ai.idIngrediente = i.idIngrediente
        GROUP BY d.idDrink";

$stmt = $connessione->prepare($sql); // questa roba ho usato gli strumenti moderni a nostra disposizione
$types = str_repeat('s', $num_ingredients) . 'i';
$params = array_merge($ingredients, [$num_ingredients]);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultati Ricerca - MixologyMate</title>
    <link rel="stylesheet" href="../mixologySyles.css">
    <style>
        /* Stili specifici per la pagina risultati */
        .risultati-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            display: flex;
            gap: 30px;
            min-height: 120vh;
        }

        .filtri-sidebar {
            width: 250px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .filtri-section {
            margin-bottom: 30px;
        }

        .filtri-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2d2d2d;
        }

        .filtro-item {
            margin-bottom: 10px;
        }

        .risultati-main {
            flex-grow: 1;
        }

        .risultato-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            gap: 20px;
            transition: transform 0.2s;
        }

        .risultato-card:hover {
            transform: translateY(-2px);
        }

        .drink-image {
            width: 250px;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            cursor: pointer;
        }

        .drink-info {
            flex-grow: 1;
        }

        .drink-title {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #2d2d2d;
            cursor: pointer;
        }

        .rating-stars {
            color: #ffb700;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .ingredienti-list {
            color: #666;
            margin-bottom: 15px;
        }

        .drink-meta {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 14px;
        }

        .salva-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .salva-button:hover {
            background: #0056b3;
        }

        .paginazione {
            text-align: center;
            margin: 30px 0;
        }

        .paginazione a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 4px;
            color: #007bff;
            text-decoration: none;
        }

        .paginazione a:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    

<div class="risultati-container">
        
        <div class="risultati-main">
            <?php if($result->num_rows > 0): ?>
                <?php while($drink = $result->fetch_assoc()): ?>
                    <div class="risultato-card">
                        
                        
                        <a href="infoDrink.php?idDrink=<?= htmlspecialchars($drink['idDrink']) ?>">
                            <img src="../mixologymate/<?= htmlspecialchars($drink['immagine']) ?>" 
                                 alt="<?= htmlspecialchars($drink['nomeDrink']) ?>" 
                                 class="drink-image">
                        </a>
                        <div class="drink-info">
                            
                            <a href="infoDrink.php?idDrink=<?= htmlspecialchars($drink['idDrink']) ?>" class="drink-title">
                                <?= htmlspecialchars($drink['nomeDrink']) ?>
                            </a>
                            
                            
                            <p class="ingredienti-list">
                                Ingredienti: <?= htmlspecialchars($drink['ingredienti']) ?>
                            </p>

                            
                            <div class="drink-meta">
                                <span> Creato da: <?= htmlspecialchars($drink['creatore']) ?></span>
                                <span><?= date('d/m/Y', strtotime($drink['dataCreazione'])) ?></span>
                            </div>

                            <button class="salva-button">Salva nei Preferiti</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="nessun-risultato">
                    <h3>Nessun drink trovato con questi ingredienti </h3>
                    <p>Prova con una combinazione diversa!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
