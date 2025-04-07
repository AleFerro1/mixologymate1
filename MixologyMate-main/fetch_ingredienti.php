<?php
include 'Connessione.php';

//header('Content-Type: application/json');

//try {
    $parola = $_GET['parola'];
    
    /*if(strlen($parola) < 2) {
        throw new Exception('Termine troppo corto');
    }*/ // questo nserve più 

    //stmt sennò ce sfondano il database
    $stmt = $connessione->prepare("SELECT nomeIngrediente FROM ingredienti WHERE nomeIngrediente LIKE ?");
    $searchTerm = "%$parola%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();

    /*if(!$stmt->execute()) {
        throw new Exception('Errore esecuzione query: ' . $stmt->error);
    }*/ //tanto nsuccede il codice è perfetto
    
    $result = $stmt->get_result();
    $ingredienti = array(); //creo array vuoto in caso la ricerca ridà più ingredienti
    
    while ($row = $result->fetch_assoc()) {
        $ingredienti[] = $row['nomeIngrediente'];
    }
    
    echo json_encode($ingredienti);//javascript deve esplodere :)
    if(isset($stmt)) $stmt->close();
    $connessione->close();

?>