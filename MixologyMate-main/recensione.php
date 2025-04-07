<?php
// recensione.php
session_start();
include 'Connessione.php';

$drink_id = $_GET['idDrink'] ;
$errori = [];
$successo = false;

// Se inviato il form di recensione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nickname'])) {
    $stelle = $_POST['stelle'];
    $titolo = trim($_POST['titolo'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    
    // Validazione
    if ($stelle < 1 || $stelle > 5) $errori[] = "Valutazione non valida";
    if (empty($titolo)) $errori[] = "Il titolo è obbligatorio";
    if (empty($descrizione)) $errori[] = "La descrizione è obbligatoria";

    if (empty($errori)) {
        $stmt = $connessione->prepare("INSERT INTO recensioni 
                                     (idDrink, nicknameCreatore, numeroStelle, titolo, descrizione, dataCreazione) 
                                     VALUES (?, ?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("isiss", $drink_id, $_SESSION['nickname'], $stelle, $titolo, $descrizione);
        
        if ($stmt->execute()) {
            $successo = true;
        } else {
            $errori[] = "Errore nel salvataggio della recensione";
        }
    }
}

// Recupera recensioni esistenti unendo la tabella utenti per ottenere l'immagine del profilo
$sql_recensioni = "
    SELECT r.*, u.immagine 
    FROM recensioni r 
    JOIN utenti u ON r.nicknameCreatore = u.nickname 
    WHERE r.idDrink = ? 
    ORDER BY r.dataCreazione DESC";
$stmt = $connessione->prepare($sql_recensioni);
$stmt->bind_param('i', $drink_id);
$stmt->execute();
$recensioni = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni - MixologyMate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body{
            background: linear-gradient(135deg, rgba(144, 255, 248, 0.5), rgba(4, 0, 115, 0.8), rgba(11, 133, 117, 0.5));
        }
        .container {
            max-width: 1000px;
            min-height: 90vh;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .recensione-card {
            margin-top: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            border-left: 4px solid #ffb700;
            cursor: pointer;
            transition: transform 0.2s;
            margin-bottom: 1rem;
        }
        .recensione-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .recensione-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            align-items: center;
        }
        .recensione-stelle {
            color: #ffb700;
            font-size: 1.2rem;
        }
        .recensione-autore {
            font-weight: bold;
            color: #2d3748;
            display: flex;
            align-items: center;
        }
        .recensione-data {
            color: #718096;
            font-size: 0.9rem;
        }
        .recensione-titolo {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: #1a202c;
        }
        /* Stili per l'immagine del profilo */
        .immagine-profilo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }
        .close-modal {
            float: right;
            cursor: pointer;
            font-size: 1.5rem;
        }
        .rating-input {
            display: flex;
            margin: 1rem 0;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ccc;
            margin-right: 0.5rem;
        }
        .rating-input input:checked ~ label,
        .rating-input label:hover,
        .rating-input label:hover ~ label {
            color: #ffb700;
        }
        .btn-primary {
            background:rgb(219, 219, 219);
            color: black;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: large;
        }
        .btn-primary:hover {
            background: #3182ce;
        }
        .error-message {
            color: #e53e3e;
            padding: 0.5rem;
            background: #fff5f5;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .success-message {
            color: #38a169;
            padding: 0.5rem;
            background: #f0fff4;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'Connessione.php'; ?>

    <div class="container">
        <h1>Recensioni</h1>
        
        <?php if(isset($_SESSION['nickname'])): ?>
            <button onclick="openModal()" class="btn-primary">
                <i class="fas fa-plus"></i> Scrivi una recensione
            </button>
        <?php else: ?>
            <a href="Login.php"><button class="btn-primary">
                Accedi per sbloccare funzione
            </button></a>
        <?php endif; ?>

        <?php if($successo): ?>
            <div class="success-message">
                Recensione inviata con successo!
            </div>
        <?php endif; ?>

        <?php if(!empty($errori)): ?>
            <div class="error-message">
                <?php foreach($errori as $errore): ?>
                    <p><?= htmlspecialchars($errore) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if($recensioni->num_rows > 0): ?>
            <?php while($recensione = $recensioni->fetch_assoc()): ?>
                <div class="recensione-card">
                    <div class="recensione-header">
                        <div class="recensione-stelle">
                            <?= str_repeat('★', $recensione['numeroStelle']) . str_repeat('☆', 5 - $recensione['numeroStelle']) ?>
                        </div>
                        <div class="recensione-autore">
                            <img src="../mixologymate/<?= htmlspecialchars($recensione['immagine']) ?>" alt="Immagine profilo di <?= htmlspecialchars($recensione['nicknameCreatore']) ?>" class="immagine-profilo">
                            <?= htmlspecialchars($recensione['nicknameCreatore']) ?>
                        </div>
                        <div class="recensione-data">
                            <?= date('d/m/Y', strtotime($recensione['dataCreazione'])) ?>
                        </div>
                    </div>
                    <h3 class="recensione-titolo"><?= htmlspecialchars($recensione['titolo']) ?></h3>
                    <p><?= htmlspecialchars($recensione['descrizione']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nessuna recensione disponibile per questo drink.</p>
        <?php endif; ?>
    </div>

    <!-- Modal per nuova recensione javascript è la morte civile-->
    <div id="recensioneModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2>Scrivi una recensione</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Titolo:</label>
                    <input type="text" name="titolo" required>
                </div>

                <div class="form-group">
                    <label>Valutazione:</label>
                    <div class="rating-input">
                        <input type="radio" id="star5" name="stelle" value="5" required>
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="stelle" value="4">
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="stelle" value="3">
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="stelle" value="2">
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="stelle" value="1">
                        <label for="star1">★</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrizione:</label>
                    <textarea name="descrizione" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn-primary">Invia Recensione</button>
            </form>
        </div>
    </div>

    <script>
        //questa è per la gestione della finestra 
        function openModal() {
            document.getElementById('recensioneModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('recensioneModal').style.display = 'none';
        }

        // Chiudi modal cliccando fuori
        window.onclick = function(event) {
            const modal = document.getElementById('recensioneModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
