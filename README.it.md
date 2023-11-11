## Theme JSON Commands

### Composer Command

#### Usage

Per generare il file `theme.json` utilizzando Composer, esegui il seguente comando nella root del tuo tema:

```shell
composer theme-json
```

#### Options

* --dry-run: Esegue il comando in modalità di prova, senza scrivere alcun file.

### WP CLI Command

#### Usage

Per generare il file theme.json utilizzando WP CLI, esegui il seguente comando nella root del tuo tema:

```shell
wp theme-json
```

#### Options

* --dry-run: Esegue il comando in modalità di prova, senza scrivere alcun file.

### Flags

Attualmente, entrambi i comandi supportano il seguente flag:

* --dry-run: Utilizza questo flag per eseguire il comando senza effettuare modifiche effettive. È utile per testare e verificare cosa farebbe il comando.