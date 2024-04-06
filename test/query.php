<?php
function trovaProdottiNoti($formato, $unita_di_misura_formato = null, $prezzo_unitario = null, $fornitore = null)
{
    include 'connessioneDB.php';
    $prodottiNoti = [];
    $formato = strtoupper($formato);
    $sql = "SELECT *
    FROM fl_listino_acquisto 
    WHERE formato LIKE '%$formato%'";
    if($unita_di_misura_formato != null)
    {
        $sql = $sql . " AND unita_di_misura_formato = '$unita_di_misura_formato'"; 
    }
    if($prezzo_unitario != null)
    {
        $sql = $sql . " AND prezzo_unitario = $prezzo_unitario";
    }
    if($fornitore != null)
    {
        $sql = $sql . " AND codice_fornitore = '$fornitore'";
    }
    $sqlEsterna = "SELECT *
    FROM ($sql) AS N, cc_prodotti AS M
    WHERE N.id_materia = M.id AND N.id_materia > 0";
    
    $result = $conn->query($sqlEsterna);
    
    if ($result !== false && $result->num_rows > 0) 
    {
        while ($row = $result->fetch_assoc()) 
        {
            $quantita = $row['valore_di_conversione'];
            $prezzo = $row['prezzo_unitario'];
            $unitaDiMisura = $row['unita_di_misura_formato'];
            $quantitaDaStringa = estraiKgDaStringa($unitaDiMisura, $row['formato']);
            if($quantitaDaStringa != -1)
            {
                $quantita = $quantitaDaStringa;
            }
            $prezzoUnitario = $quantita/$prezzo;
            $nuovoArray = [];
            $nuovoArray['ID'] =  $row['id'];
            $nuovoArray['NOME'] =  $row['nome'];
            $nuovoArray['UNITA_DI_MISURA'] =  $unitaDiMisura;
            $nuovoArray['QUANTITA'] =  $quantita;
            $nuovoArray['PREZZO_UNITARIO'] = $prezzoUnitario;
            array_push($prodottiNoti, $nuovoArray);
        }
    }
    
    return $prodottiNoti;
}

function estraiKgDaStringa($unitaDiMisura, $stringa)
{
    //Per il caso dei chilogrammi, ho notato una discrepanza tra il valore di conversione
    //e la quantità dichiarata nella stringa formato.
    //in tal caso ho ritenuto opportuno sovrascrivere il valore di conversione con
    //il valore trovato nella stringa.
    //ESEMPIO: formato riporta -> Mortadella da 5Kg con un valore di conversione 1.
    //Ho ritenuto meglio considerare 5 e non 1.
    $quantita = -1;
    if(preg_match("/([0-9]+) ?$unitaDiMisura/i", $stringa, $risultato))
    {
        if($unitaDiMisura == 'KG')
        {
            $quantita = (float)$risultato[1];
        }
    }else if(preg_match("/$unitaDiMisura ?([0-9]+)/i", $stringa, $risultato))
    {
        if($unitaDiMisura == 'KG')
        {
            $quantita = (float)$risultato[1];
        }
    }

    return $quantita;
}

function stampaListaProdotti($lista)
{
    for($i = 0; $i < count($lista); $i++)
    {
        $stringa = implode(", ", $lista[$i]);
        echo $stringa;
        echo "\n";
    }
}

/** Funzione che cerca i prodotti che non sono associabili direttamente all'articolo tramite id_materia
 *  per cui effettua una ricerca testuale utilizzando la parola "formato".
 *  e poi 
 * metodo 1 - usa 'formato' (query agli articoli tramite la stringa formato; cerca quelli che matchano nella tabella prodotti, se non ho trovato nulla, si divide 'formato' in parole e faccio n query per sui prodotti per le n parole.)
 * metodo 2 - usa 'codice_fornitore' come metodo di backup nel caso il primo metodo non abbia trovato prodotti associabili.
 *  
*/
function trovaProdottiNonNoti($formato, $unita_di_misura_formato = null, $prezzo_unitario = null, $fornitore = null)
{
    include 'connessioneDB.php';
    $arrayProdottiNonNoti = [];
    $formato = strtoupper($formato);
    $sql = "SELECT *
    FROM fl_listino_acquisto 
    WHERE formato LIKE '%$formato%'";
    if($unita_di_misura_formato != null)
    {
        $sql = $sql . " AND unita_di_misura_formato = '$unita_di_misura_formato'"; 
    }
    if($prezzo_unitario != null)
    {
        $sql = $sql . " AND prezzo_unitario = $prezzo_unitario";
    }
    if($fornitore != null)
    {
        $sql = $sql . " AND fornitore = $fornitore";
    }
    $result = $conn->query($sql);
    if($result !== false && $result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            // ricerca prodotti associabili tramite stringa formato
            $formatoIntero = $row["formato"];
            $sqlProdottiNonNoti = "SELECT *
                FROM cc_prodotti
                WHERE nome LIKE '%$formatoIntero%'";
                
                $resultProdottiNonNoti = $conn->query($sqlProdottiNonNoti);

                if ($resultProdottiNonNoti !== false && $resultProdottiNonNoti->num_rows > 0) 
                {   
                    while($rowProdottiNonNoti = $resultProdottiNonNoti->fetch_assoc())
                    {
                        $quantita = $row['valore_di_conversione'];
                        $unitaDiMisura = $row['unita_di_misura_formato'];
                        $quantitaDaStringa = estraiKgDaStringa($unitaDiMisura, $formatoIntero);
                        if($quantitaDaStringa != -1)
                        {
                            $quantita = $quantitaDaStringa;
                        }
                        $prezzo = $row['prezzo_unitario'];
                        $nomeProdotto = $rowProdottiNonNoti['nome'];
                        $idProdotto = $rowProdottiNonNoti['id'];
                        $nuovoArray = [];
                        $nuovoArray['ID'] =  $idProdotto;
                        $nuovoArray['NOME'] =  $nomeProdotto;
                        $nuovoArray['UNITA_DI_MISURA'] =  $unitaDiMisura;
                        $nuovoArray['QUANTITA'] =  $quantita;
                        $nuovoArray['PREZZO_UNITARIO'] = $prezzoUnitario;
                        array_push($arrayProdottiNonNoti, $nuovoArray);
                    } 
                }else
                {
                    // ricerca parola per parola se la ricerca prodotti con il formato intero non ha dato risultati
                    $formatoInParole = explode(" ", $formatoIntero);
                    for ($i = 0; $i < count($formatoInParole); $i++)
                    {
                        $parola =  $formatoInParole[$i];
                        $sqlProdottiNonNoti = "SELECT *
                        FROM cc_prodotti
                        WHERE nome LIKE '%$parola%'";
                    }

                    $resultProdottiNonNoti = $conn->query($sqlProdottiNonNoti);
                    if ($resultProdottiNonNoti !== false && $resultProdottiNonNoti->num_rows > 0) 
                    {
                        while($rowProdottiNonNoti = $resultProdottiNonNoti->fetch_assoc())
                        {   
                            $quantita = $row['valore_di_conversione'];
                            $unitaDiMisura = $row['unita_di_misura_formato'];
                            $quantitaDaStringa = estraiKgDaStringa($unitaDiMisura, $formatoIntero);
                            if($quantitaDaStringa != -1)
                            {
                                $quantita = $quantitaDaStringa;
                            }
                            $prezzo = $row['prezzo_unitario'];
                            $nomeProdotto = $rowProdottiNonNoti['nome'];
                            $idProdotto = $rowProdottiNonNoti['id'];
                            $nuovoArray = [];
                            $nuovoArray['ID'] =  $idProdotto;
                            $nuovoArray['NOME'] =  $nomeProdotto;
                            $nuovoArray['UNITA_DI_MISURA'] =  $unitaDiMisura;
                            $nuovoArray['QUANTITA'] =  $quantita;
                            $nuovoArray['PREZZO_UNITARIO'] = $prezzoUnitario;
                            array_push($arrayProdottiNonNoti, $nuovoArray);
                        } 
                    }
                }
        }  
    }
    if(count ($arrayProdottiNonNoti) == 0 && $fornitore != null)
    {
        //ricerca di backup tramite codice fornitore
        $sqlCodiceFornitore = "SELECT *
        FROM fl_listino_acquisto 
        WHERE codice_fornitore LIKE '%$formato%'";
        if($fornitore != null)
        {
            $sqlCodiceFornitore = $sqlCodiceFornitore . " AND fornitore = $fornitore";
        }
        $result = $conn->query($sqlCodiceFornitore);
        if($result !== false && $result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {
                $formatoIntero = $row["formato"];
                $sqlProdottiNonNoti = "SELECT *
                    FROM cc_prodotti
                    WHERE nome LIKE '%$formatoIntero%'";
                    
                    $resultProdottiNonNoti = $conn->query($sqlProdottiNonNoti);
    
                    if ($resultProdottiNonNoti !== false && $resultProdottiNonNoti->num_rows > 0) 
                    {
                        while($rowProdottiNonNoti = $resultProdottiNonNoti->fetch_assoc())
                        {
                            $quantita = $row['valore_di_conversione'];
                            $unitaDiMisura = $row['unita_di_misura_formato'];
                            $quantitaDaStringa = estraiKgDaStringa($unitaDiMisura, $formatoIntero);
                            if($quantitaDaStringa != -1)
                            {
                                $quantita = $quantitaDaStringa;
                            }
                            $prezzo = $row['prezzo_unitario'];
                            $nomeProdotto = $rowProdottiNonNoti['nome'];
                            $idProdotto = $rowProdottiNonNoti['id'];
                            $nuovoArray = [];
                            $nuovoArray['ID'] =  $row['id'];
                            $nuovoArray['NOME'] =  $row['nome'];
                            $nuovoArray['UNITA_DI_MISURA'] =  $unitaDiMisura;
                            $nuovoArray['QUANTITA'] =  $quantita;
                            $nuovoArray['PREZZO_UNITARIO'] = $prezzoUnitario;
                            array_push($arrayProdottiNonNoti, $nuovoArray);
                        } 
                    }
            }  
        }
    }
    return $arrayProdottiNonNoti;    
}

/** * Questa è la funzione principale per la ricerca di prodotti dati gli articoli identificati dai parametri che vengono in input che sono:. * *
@param string $formato stringa dell'articolo da cercare.
@param string $unita_di_misura_formato è l'unità di misura. *
@param float $prezzo_unitario è il prezzo unitario dell'articolo da cercare 
@param int $fornitore è l'identificativo del fornitore.
@return $risultato array di possibili prodotti rappresentati da:
- id 
- descrizione del prodotto 
- unita di misura del prodotto 
- quantita approvvigionata rilevata 
- costo prodotto
*/
function trovaProdotti($formato, $unita_di_misura_formato = null, $prezzo_unitario = null, $fornitore = null)
{
    $risultato = trovaProdottiNoti($formato, $unita_di_misura_formato, $prezzo_unitario, $fornitore);
    if(count($risultato) == 0)
    {
       $risultato = trovaProdottiNonNoti($formato, $unita_di_misura_formato, $prezzo_unitario, $fornitore);
    }
    return $risultato;
}

?>