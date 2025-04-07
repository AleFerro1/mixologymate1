<?php
session_start();
include 'Connessione.php';

// Gestione ricerca
$ricerca = '';
if(isset($_GET['search'])) {
    $ricerca = trim($_GET['search']);
}

// questa è la query pe la barra di ricerca
$sql = "SELECT d.*, 
        AVG(r.numeroStelle) AS valutazione_media,
        COUNT(r.idRecensione) AS numero_recensioni
        FROM drink d
        LEFT JOIN recensioni r ON d.idDrink = r.idDrink
        WHERE d.nomeDrink LIKE ?
        GROUP BY d.idDrink
        ORDER BY d.nomeDrink ASC";

$stmt = $connessione->prepare($sql);
$ricercaParam = "%$ricerca%";
$stmt->bind_param('s', $ricercaParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutti i Drink - MixologyMate</title>
    <link rel="stylesheet" href="mixologySyles.css">
    <style>
        body{
            background: linear-gradient(135deg, rgba(144, 255, 248, 0.5), rgba(4, 0, 115, 0.8), rgba(11, 133, 117, 0.5));
        }
        .drinks-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.59);
        }
        
        .drinks-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .drink-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .drink-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        
        .drink-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .drink-info {
            padding: 15px;
        }
        
        .drink-name {
            font-size: 1.2rem;
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .drink-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #ffb700;
            font-weight: bold;
        }
        
        .drink-reviews {
            font-size: 0.8rem;
            color: #666;
            margin-left: 5px;
        }
        
        .page-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
        }
        
        @media (max-width: 900px) {
            .drinks-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 600px) {
            .drinks-grid {
                grid-template-columns: 1fr;
            }
        }
        .search-bar-container {
            max-width: 800px;
            margin: 0 auto 100px ;
            padding: 0 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .search-input:focus {
            border-color: #007bff;
        }
        
        .search-button {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .search-button:hover {
            background-color: #0056b3;
        }
        
        .reset-button {
            padding: 12px 20px;
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .reset-button:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <?php include 'Connessione.php'; ?>
    
    <div class="drinks-container">
        <h1 class="page-title">Esplora tutti i nostri drink</h1>
        
        
        <div class="search-bar-container">
            <form class="search-form" method="GET" action="">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Cerca drink per nome..." 
                       value="<?= htmlspecialchars($ricerca) ?>">
                
                <button type="submit" class="search-button">Cerca</button>
                
                <?php if($ricerca): ?>
                    <a href="?" class="reset-button">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        
        
        <?php if($result->num_rows > 0): ?>
            <div class="drinks-grid">
                <?php while($drink = $result->fetch_assoc()): ?>
                    <div class="drink-card">
                        <a href="infoDrink.php?idDrink=<?= $drink['idDrink'] ?>">
                            <img src="../mixologymate/<?= htmlspecialchars($drink['immagine']) ?>" 
                                 alt="<?= htmlspecialchars($drink['nomeDrink']) ?>" 
                                 class="drink-image">
                        </a>
                        <div class="drink-info">
                            <h3 class="drink-name"><?= htmlspecialchars($drink['nomeDrink']) ?></h3>
                            <div class="drink-rating">
                                <?php if($drink['valutazione_media']): ?>
                                    <?= str_repeat('★', round($drink['valutazione_media'])) ?>
                                    <?= number_format($drink['valutazione_media'], 1) ?>
                                    <span class="drink-reviews">(<?= $drink['numero_recensioni'] ?>)</span>
                                <?php else: ?>
                                    <span>Nessuna valutazione</span>
                                <?php endif; ?>
                            </div>
                            <p>Creato da: <?= htmlspecialchars($drink['creatore']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>Nessun drink trovato<?= $ricerca ? ' per "' . htmlspecialchars($ricerca) . '"' : '' ?></p>
                <?php if($ricerca): ?>
                    <a href="?" class="reset-button">Mostra tutti i drink</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    
</body>
</html>