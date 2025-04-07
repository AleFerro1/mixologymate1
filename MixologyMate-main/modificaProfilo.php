<?php
session_start();
include 'Connessione.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nickname = $_POST['nickname'] ;
    $originalNickname = $_SESSION['nickname'] ;
    $newNickname = $_POST['nickname'] ;
    $email = $_POST['email'] ;
    $descrizione = $_POST['descrizione'] ;
    
    // Percorso assoluto della cartella di upload (relativo alla root del progetto)
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/mixologymate/uploads/profiles/';
    


    // Gestione upload immagine :):):):):):):):):):):):):)
    if(isset($_FILES['profilePhoto'])) {
        $file = $_FILES['profilePhoto'];
            $nuovoNome = 'profile_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $path = $uploadDir . $nuovoNome;
            
            move_uploaded_file($file['tmp_name'], $path); 
                
            $connessione->query("UPDATE utenti SET immagine = 'uploads/profiles/$nuovoNome' 
                                    WHERE nickname = '$originalNickname'");
            
         
    }
    
    // Verifica se il nuovo nickname è già in uso (solo se è cambiato)
    if($newNickname !== $originalNickname) {
        $checkSql = "SELECT * FROM utenti WHERE nickname = ? AND nickname != ?";
        $checkStmt = $connessione->prepare($checkSql);
        $checkStmt->bind_param('ss', $newNickname, $originalNickname);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if($result->num_rows > 0) {
            //$_SESSION['errore'] = "Il nickname è già in uso!";
            header("Location: profilo.php");
            exit;
        }
    }

    // Aggiorna i dati del profilo hgsdutiwoeytruiwqetyuiweqygtuweqyrhui oh hell nah man
    $updateSql = "UPDATE utenti SET nickname = ?, email = ?, descrizione = ? WHERE nickname = ?";
    $updateStmt = $connessione->prepare($updateSql);
    $updateStmt->bind_param('ssss', $newNickname, $email, $descrizione, $originalNickname);

    if($updateStmt->execute()) {
        $_SESSION['nickname'] = $newNickname; // Aggiorna la sessione
        //$_SESSION['successo'] = "Profilo aggiornato con successo!";
        
        // Aggiorna anche l'immagine nella sessione se è stata caricata una nuova
        if(isset($relativePath)) {
            $_SESSION['immagine'] = $relativePath;
        }
    } else {
        $_SESSION['errore'] = "Errore durante l'aggiornamento: " . $updateStmt->error;
    }
    
    header('Location: profiloImpostazioni.php');
    exit;
}
?>