<?php
session_start();
include 'Connessione.php';


$successo = false;
$idDrink = null;

// Verifica login
if (!isset($_SESSION['nickname'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validazione campi
    $nome =trim($_POST['nome'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $ingredienti = $_POST['ingredienti'] ?? [];
    $creatore = $_SESSION['nickname'];

    

    // Gestione immagine
    $immaginePath = ''; //ho ricopiato da profilo 
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
        $estensione = strtolower(pathinfo($_FILES['immagine']['name'], PATHINFO_EXTENSION));
        $estensioniPermesse = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($estensione, $estensioniPermesse)) {
            $nomeFile = uniqid('drink_') . '.' . $estensione;
            $cartellaUpload = '../uploads/drinks/';
            
            if (!is_dir($cartellaUpload)) {
                mkdir($cartellaUpload, 0755, true);
            }
            
            $destinazione = $cartellaUpload . $nomeFile;
            if (move_uploaded_file($_FILES['immagine']['tmp_name'], $destinazione)) {
                $immaginePath = $destinazione;
            } 
        } 
    } 

    
        
        
        
            // Inserimento drink
            $stmtDrink = $connessione->prepare("INSERT INTO drink (nomeDrink, descrizione, creatore, immagine, dataCreazione) 
                                             VALUES (?, ?, ?, ?, CURDATE())");
            $stmtDrink->bind_param("ssss", $nome, $descrizione, $creatore, $immaginePath);
            $stmtDrink->execute();
            $idDrink = $stmtDrink->insert_id;

            // Gestione ingredienti
            foreach ($ingredienti as $ing) {
                $nomeIng = trim($ing['nome']);
                $quantita = trim($ing['quantita']);

                
                $stmtGetId = $connessione->prepare("SELECT idIngrediente FROM ingredienti WHERE nomeIngrediente = ?");
                $stmtGetId->bind_param("s", $nomeIng);
                $stmtGetId->execute();
                $result = $stmtGetId->get_result();
                // Controllo e inserimento ingrediente
                if($result->num_rows===0){
                    $stmtIng = $connessione->prepare("INSERT INTO ingredienti (nomeIngrediente) VALUES (?)");
                    $stmtIng->bind_param("s", $nomeIng);
                    $stmtIng->execute();
                    /*$stmt=$connessione->prepare('SELECT idIngrediente from ingredienti where nomeIngrediente = ?');
                    $stmt->bind_param('s',$nomeIng);
                    $stmt->execute();
                    $result=$stmt->get_result();
                    $idIngrediente=$result->fetch_assoc();*/
                    $idIngrediente = $connessione->insert_id; //insert id prende l'id auto incrementato nell'insert 
                }
                else{
                    $idIngrediente=$result->fetch_assoc()['idIngrediente'];
                }
                

                // Recupero ID ingrediente
                /*$stmtGetId = $connessione->prepare("SELECT idIngrediente FROM ingredienti WHERE nomeIngrediente = ?");
                $stmtGetId->bind_param("s", $nomeIng);
                $stmtGetId->execute();*/
                

                //Qua associo tutto 
                $stmtAss = $connessione->prepare("INSERT INTO associazioneIngredienti (idDrink, idIngrediente, quantità) 
                                                 VALUES (?, ?, ?)");
                $stmtAss->bind_param("iis", $idDrink, $idIngrediente, $quantita);
                $stmtAss->execute();
            }

            
            $successo = true;
        
    
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Drink - MixologyMate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Stili invariati dalla versione precedente */
        body{
            background: linear-gradient(135deg, rgba(144, 255, 248, 0.5), rgba(4, 0, 115, 0.8), rgba(11, 133, 117, 0.5));
        }
        .creation-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            min-height: 120vh;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
        }

        input, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .ingredienti-container {
            margin: 1rem 0;
        }

        .ingredient-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .ingredient-row input {
            flex: 1;
        }

        .add-ingredient {
            background: #4299e1;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .remove-btn {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .error-message {
            color: #e53e3e;
            margin: 1rem 0;
            padding: 1rem;
            background: #fff5f5;
            border-radius: 6px;
        }

        .success-message {
            color: #38a169;
            margin: 1rem 0;
            padding: 1rem;
            background: #f0fff4;
            border-radius: 6px;
        }

        .image-preview {
            width: 200px;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px dashed #cbd5e0;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="creation-container">
        <h1>🍹 Crea il tuo Drink</h1>

        

        <?php if ($successo): ?>
            <div class="success-message">
                Drink creato con successo! <br>
                <a href="infoDrink.php?id=<?= $idDrink ?>">Visualizza il drink</a>
            </div>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome del Drink:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="descrizione">Descrizione:</label>
                    <textarea id="descrizione" name="descrizione" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Immagine del Drink:</label>
                    <input type="file" name="immagine" accept="image/*" required>
                    <img id="preview" class="image-preview" src="#" alt="Anteprima" style="display: none;">
                </div>

                <div class="form-group">
                    <label>Ingredienti:</label>
                    <div class="ingredienti-container" id="ingredientiContainer">
                        <div class="ingredient-row">
                            <input type="text" name="ingredienti[0][nome]" placeholder="Nome ingrediente" required>
                            <input type="text" name="ingredienti[0][quantita]" placeholder="Quantità (es. 50ml)" required>
                            <button type="button" class="remove-btn">×</button>
                        </div>
                    </div>
                    <button type="button" class="add-ingredient" onclick="addIngredient()">
                        <i class="fas fa-plus"></i> Aggiungi Ingrediente
                    </button>
                </div>

                <button type="submit" class="add-ingredient">Crea Drink</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        let ingredientCount = 1;

        function addIngredient() {
            if(ingredientCount<10){
            const container = document.getElementById('ingredientiContainer');
            const newRow = document.createElement('div');
            newRow.className = 'ingredient-row';//qua creo il tasto pe mette gli ingredienti
            newRow.innerHTML = `
                <input type="text" name="ingredienti[${ingredientCount}][nome]" placeholder="Nome ingrediente" required>
                <input type="text" name="ingredienti[${ingredientCount}][quantita]" placeholder="Quantità" required>
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(newRow);
            ingredientCount++;
            }
            else{
                alert("Troppi ingredienti trimone");
            }
        }

        // 
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                preview.style.display = 'block';
                preview.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
</body>
</html>