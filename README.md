# tipi di dipendenza

- classe usata in un'altra classe
- classe estesa
- interfaccia implementata
- classe anonima estende classe, implementa interfaccia
- uso di una funzione con namespace
- uso di una funzione

# possibili modellazioni

- arco dipendenza
- albero (grafo) dipendenze
- lista di simboli e dipendenze di ogni simbolo
  - si costruisce mentre si parsa il codice
  - si raggruppa usando i namespace
  - ci deve essere un identificatore univoco per ogni simbolo
  - tipi di dipendenze
    - uso di una classe
    - uso di una funzione
    - uso di una costante
    - implementazione di un'interfaccia
    - estensione di una classe
  - modalità di importazione/riferimento
    - fqcn
    - use prima della definizione
    - fuzioni senza namespace
    - classi nello stesso namespace
    - classi in sottonamespace
    - classi in sottonamespace dei namespace contenuti nello use
