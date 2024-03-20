## Node version compatibility

This package is tested with Node 18.0.0 and npm 8.6.0.

## How to contribute

`yarn link` è un comando che ti permette di "collegare" il tuo pacchetto locale in un altro progetto. Questo è utile quando stai sviluppando un pacchetto e vuoi testarlo in un altro progetto senza doverlo pubblicare su npm ogni volta che fai una modifica.

Ecco come funziona:

1. Nella directory del tuo pacchetto (in questo caso, la directory `theme-json-generator`), esegui il comando `yarn link`. Questo creerà un link simbolico globale al tuo pacchetto.

```bash
cd theme-json-generator
yarn link
```

2. Nella directory del progetto in cui desideri utilizzare il tuo pacchetto, esegui il comando `yarn link theme-json-generator`. Questo creerà un link simbolico nella directory `node_modules` del tuo progetto al tuo pacchetto globale.

```bash
cd percorso/al/tuo/progetto
yarn link theme-json-generator
```

Ora, quando richiedi `theme-json-generator` nel tuo progetto, verrà utilizzata la versione locale del tuo pacchetto. Qualsiasi modifica apporti al pacchetto locale sarà immediatamente riflessa nel tuo progetto.

Per quanto riguarda il comando `theme-json`, dovrebbe funzionare come previsto, a patto che tu abbia definito correttamente il campo `bin` nel tuo `package.json`.

Ricorda che per rimuovere il link, puoi utilizzare il comando `yarn unlink` sia nel tuo pacchetto che nel tuo progetto.

## Installation

Se il tuo pacchetto non è installato globalmente, puoi comunque eseguire il comando `theme-json` utilizzando `npx` o tramite gli script npm nel tuo `package.json`.

1. Utilizzando `npx`:

```bash
npx theme-json
```

`npx` è uno strumento che viene fornito con `npm` e ti permette di eseguire comandi di pacchetti npm senza doverli installare globalmente.

2. Aggiungendo uno script npm nel tuo `package.json`:

```json
"scripts": {
    "theme-json": "theme-json"
}
```

Quindi, puoi eseguire il comando con `npm run`:

```bash
npm run theme-json
```

In entrambi i casi, il comando `theme-json` sarà eseguito.