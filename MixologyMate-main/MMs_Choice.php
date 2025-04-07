<?php 
//QUESTO LO STO ANCORA A FA
    include 'Connessione.php';
    $sql='SELECT AVG(R.numeroStelle) as valutazione_media, D.idDrink, D.descrizione,
        D.nomeDrink, D.immagine, COUNT(R.idRecensione) as numero_recensioni, D.creatore
        FROM drink as D LEFT JOIN recensioni as R ON D.idDrink=R.idDrink
        where D.idDrink=1';
    $result=$connessione->query($sql);
    $drink=$result->fetch_assoc();
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .drink-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .image-wrapper {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 4px solid #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .drink-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .drink-info {
            flex: 1;
            padding-right: 30px;
        }
        
        .drink-name {
            font-size: 1.8rem;
            margin: 0 0 15px 0;
            color: #333;
        }
        
        .drink-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        .drink-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffb700;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .drink-reviews {
            font-size: 0.9rem;
            color: #666;
            margin-left: 5px;
        }
        
        .creator-info {
            color: #888;
            font-size: 0.95rem;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .drink-card {
                flex-direction: column;
                text-align: center;
            }
            
            .image-wrapper {
                width: 200px;
                height: 200px;
            }
            
            .drink-info {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="drink-card">
        <div class="image-wrapper">
            <img src="../mixologymate/<?= htmlspecialchars($drink['immagine']) ?>" 
                 alt="<?= htmlspecialchars($drink['nomeDrink']) ?>" 
                 class="drink-image">
        </div>
        
        <div class="drink-info">
            <h3 class="drink-name"><?= htmlspecialchars($drink['nomeDrink']) ?></h3>
            <p class="drink-description"><?= htmlspecialchars($drink['descrizione']) ?></p>
            <div class="drink-rating">
                <?php if($drink['valutazione_media']): ?>
                    <?= str_repeat('★', round($drink['valutazione_media'])) ?>
                    <?= number_format($drink['valutazione_media'], 1) ?>
                    <span class="drink-reviews">(<?= $drink['numero_recensioni'] ?> recensioni)</span>
                <?php else: ?>
                    <span>Nessuna valutazione</span>
                <?php endif; ?>
            </div>
            <p class="creator-info">Creato da: <?= htmlspecialchars($drink['creatore']) ?></p>
        </div>
    </div>
</body>
</html>