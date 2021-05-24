# HW1
La pagina da aprire all'ingresso è index.php, che presenta la descrizione del progetto e riporta i link di accesso alle altre pagine.

## Eventi - events.php
Accessibile solo dopo aver effettuato il login, infatti quando si prova ad accedere riporta alla pagina login.php. Mostra tutti gli eventi in programma per il futuro sulla sinistra oppure filtrandoli per numero e a seconda dei Direttori Artistici seguiti, al centro l'evento di cui si vogliono visualizzare più informazioni, di default è l'evento più prossimo, infine sulla destra una serie di video caricati tramite API con autenticazione OAuth2.0 ricercati tramite i tags impostati sugli eventi.

## Esplora - explore.php
Prima pagina visualizzata dopo il login. Mostra le informazioni dell'utente che ha effettuato l'accesso e l'insieme dei post che ha pubblicato quest'ultimo. Al centro è possibile pubblicare un'opera d'arte, recensirla e attribuire una valutazione in stelle. Le opere vengono scelte tramite una API, aperta in visualizzazione modale; quando vengono recensite sono dinamicamente inserite nel database. La stessa sezione mostra le opere pubblicate da altri a cui poter aggiungere un like. 
Sulla destra l'insieme dei Direttori Artistici che organizzano gli eventi. Quest'ultimi possono essere seguiti e caricati in evidenza.

## Admin - admin.php
Pagina accessibile solo dall'admin dichiarato nelle impostazioni del database in dbconfig.php. Permette di inserire gli elementi del database quali nuovi Direttori Artistici, Guide ed Eventi. Vengono effettuati controlli che dopo esiti positivi aggiungono al database.

## Login e Signup - login.php e signup.php
Effettuano i normali controlli, come verifica di lunghezza della password e la presenza di minuscole, maiscole e numeri. Gestiscono le sessioni e i cookies con l'interazione del database.

### Note
Ogni richiesta è effettuata tramite fetch evitando caricamenti della pagina.
