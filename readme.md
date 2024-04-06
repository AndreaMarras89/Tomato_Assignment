### Descrizione applicazione

---

L'applicazione consiste in un endpoint Rest (GET) prodotti che, dati i criteri di ricerca per un articolo ( `formato` , `fornitore` , `unita_di_misura_formato` ,
`prezzo_unitario` ), restituisce una lista di possibili prodotti associabili all'articolo; ciascun prodotto à rappresentato da:

- identificativo del prodotto (intero)
- descrizione del prodotto (stringa)
- unità di misura (stringa)
- quantità di prodotto rifornata dall'articolo (intero)
- prezzo unitario prodotto (float)

### Componenti applicazione

---

L'applicazione si compone di un web server per esporre l'endpoint Rest, di un database e della logica applicativa vera e propria in PHP.

L'endpoint Rest è invocabile come:

```bash
<SERVER_UL>/test/index.php/prodotti?formato=<VAL>&unita_di_misura_formato=<VAL>&fornitore=<VAL>&prezzo_unitario=<VAL>
```

I parametri di input sono:

-`fomato` : stringa, obbligatorio -`unita_di_misura_formato` : stringa, opzionale -`fornitore` : intero, opzionale -`prezzo_unitario` : float, opzionale

Un esempio:

```bash
http://localhost/test/index.php/prodotti?formato=MORTADELLA&unita_di_misura_formato=KG
```

Risultato:

```bash
[
    {
        "ID": "246",
        "NOME": "MORTADELLA",
        "UNITA_DI_MISURA": "KG",
        "QUANTITA": 5,
        "PREZZO_UNITARIO": 0.5050505050505051
    },
    {
        "ID": "793",
        "NOME": "MORTADELLA BONFANTI",
        "UNITA_DI_MISURA": "KG",
        "QUANTITA": "1.00",
        "PREZZO_UNITARIO": 0.06756756756756756
    },
    {
        "ID": "246",
        "NOME": "MORTADELLA",
        "UNITA_DI_MISURA": "KG",
        "QUANTITA": "1.00",
        "PREZZO_UNITARIO": 0.06756756756756756
    }
]
```

### Logica funzioni principali

---

Le funzionalità principali sono racchiuse in due funzioni: `trovaProdottiNoti` e `trovaProdottiNonNoti`. Entrambe sono chiamate dalla funzione principale
`trovaProdotti`, che prima effettua la chiamata a trova `trovaProdottiNoti`, e poi, nel caso questa non restituisca risultati, invoca `trovaProdottiNonNoti`.

<strong>funzione</strong> `trovaProdottiNoti`

Cerca tutti i prodotti noti associabili agli articoli compatibili con i parametri ricevuti dalla chiamata Rest. Con `prodotti noti` si intendono quei prodotti che sono
associabili agli articoli tramite `id_materia`, ossia quando è noto quale prodotto venga rifornito da un determinato articolo. In caso `id_materia` sia 0 o -1, il
prodotto è da considerarsi _non noto_. Dato che il formato è l'unico parametro di input obbligatorio, gli altri filtri della query (fornitore, prezzo ecc.) vengono aggiunti dinamicamente alla query se non sono `null`.

In questo caso, i prodotti vengono immediatamente trovati nel DB tramite una query che effettua direttamente la join delle due tabelle `e` sugli id di prodotto. Per
esempio:

```bash
SELECT *
FROM (SELECT *
    FROM fl listino_acquisto
    WHERE formato LIKE '%MORTADELLA%' AND fornitore = 25) AS N, cc_prodotti AS M
WHERE N.id_materia = M.id AND N.id_materia > 0
```

<strong>funzione</strong> `trovaProdottiNonNoti`

Cerca tutti i prodotti associabili agli articoli compatibili con i parametri ricevuti dalla chiamate Rest, utilizzando due criteri:

- ricerca testuale tramite campo formato
- ricerca testuale tramite campo codice_fornitore

La ricerca tramite `formato` avviene in due passaggi: prima cercando prodotti la cui descrizione possa corrispondere all'intera stringa `formato`. Esempio:

```bash
SELECT *
FROM fl_listino_acquisto
WHERE formato LIKE '%MORTADELLA%'
```

In caso di assenza di risultati, si procede alla ricerca di prodotti che corrispondono alle single parole contenute nel campo `formato`. Si considerano singole parole le sotto-stringhe separate da spazio.

Se non viene trovato alcun prodotto tramite i passaggi appena descritti, un ulteriore tentativo di ricerca viene fatto cercando gli articoli il cui `codice_fornitore` contenga la stringa `formato` passata dall'utente nella query di ricerca (chiamata Rest).

```bash
SELECT *
FROM fl_listino_acquisto
WHERE codice_fornitore LIKE '%COPERTO%'
```

### Step per installazione e configurazione

---

- Avviare web server e database. Io ho usato XAMPP
- Creare nel database le tabelle dati tramite gli script .sql forniti nel codice
- Copiare la cartella test nella cartella htdocs di XAMPP
- Configurare lo script connessioneDB.php con i parametri di connessione del DB (host, username ecc.)
