
1. Comando:

   Viene invocato direttamente come uno script o un eseguibile.
   Ad esempio: @php ./vendor/bin/theme-json o semplicemente theme-json se è nel PATH.

2. Plugin:

   Non viene solitamente invocato direttamente dall'utente.
   Interviene automaticamente durante specifiche fasi di esecuzione di Composer. Ad esempio, un plugin può essere eseguito automaticamente dopo l'installazione di un pacchetto.
   Se un plugin fornisce comandi specifici, possono essere invocati come comandi di Composer. Ad esempio: composer some-plugin-command.


Layer Dominio:
    src/Domain/
        Entity/: Entità del dominio.
        ValueObject/: Oggetti valore.
        Repository/: Interfacce dei repository.
        Event/: Eventi del dominio.
        Service/: Servizi di dominio.
        Exception/: Eccezioni specifiche del dominio.

Layer Applicativo:
    src/Application/
        Service/: Servizi applicativi, che orchestano l'uso delle entità e dei servizi di dominio.
        Command/: Comandi che rappresentano le richieste di input.
        Query/: Query per richiedere dati.
        DTO/: Data Transfer Objects, utilizzati per trasferire dati tra layer.

Layer Infrastruttura:
    src/Infrastructure/
        Repository/: Implementazioni concrete dei repository, ad es. per database o sistemi esterni.
        Service/: Servizi specifici dell'infrastruttura, come servizi di email o logging.
        Persistence/: Codice legato alla persistenza dei dati, come ORM o database.
        Event/: Implementazioni per la gestione degli eventi, come event listeners o subscribers.

Layer Presentazione:
    src/Presentation/
        Web/: Controller, template e risorse per l'interfaccia web.
        CLI/: Comandi da riga di comando, come il tuo Command.php.
        API/: Endpoint e risorse per le API.

Layer Test:
    tests/: Test unitari, funzionali e di integrazione.

Configurazione e Risorse:
    config/: File di configurazione.
    resources/: Risorse come immagini, traduzioni, ecc.

Root:
    composer.json, README.md, ecc.


Estendere i comandi di Composer con un plugin richiede alcuni passi specifici. Ecco una guida passo-passo su come farlo:

1. Creare una Classe per il Comando:

   La tua classe comando deve estendere Composer\Command\BaseCommand.
   Implementa il metodo configure() per definire il nome del comando, gli argomenti, le opzioni e la descrizione.
   Implementa il metodo execute() per definire ciò che il comando farà quando verrà eseguito.

    ```php
    namespace YourNamespace;
    
    use Composer\Command\BaseCommand;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    
    class YourCommand extends BaseCommand {
        protected function configure() {
            $this->setName('your-command-name')
                 ->setDescription('Descrizione del tuo comando.');
        }
    
        protected function execute(InputInterface $input, OutputInterface $output) {
            // Il tuo codice per eseguire il comando
            $output->writeln('Il tuo comando è stato eseguito!');
        }
    }
    ```

2. Registra il Comando nel Plugin:

   La tua classe plugin deve implementare Composer\Plugin\PluginInterface e Composer\Plugin\Capable.
   Nel metodo getCapabilities() della tua classe plugin, indica che il tuo plugin fornisce comandi.

    ```php
    namespace YourNamespace;
    
    use Composer\Plugin\PluginInterface;
    use Composer\Plugin\Capable;
    use Composer\Plugin\CommandProvider as CommandProviderCapability;
    
    class YourPlugin implements PluginInterface, Capable {
        // ... altri metodi del plugin ...
    
        public function getCapabilities() {
            return array(
                CommandProviderCapability::class => 'YourNamespace\YourCommandProvider',
            );
        }
    }
    ```
   
3. Fornire il Comando con un CommandProvider:

   Crea una classe YourCommandProvider che implementa Composer\Plugin\Capability\CommandProvider.
   Nel metodo getCommands(), restituisci un'istanza del tuo comando.

    ```php
    namespace YourNamespace;
    
    use Composer\Plugin\Capability\CommandProvider;
    
    class YourCommandProvider implements CommandProvider {
        public function getCommands() {
            return array(new YourCommand());
        }
    }
    ```
   
4. Aggiorna il tuo composer.json:

   Assicurati che il tuo composer.json definisca il tuo plugin e le sue classi.

    ```json
    {
        "name": "your/package-name",
        "type": "composer-plugin",
        "require": {
            "composer-plugin-api": "^1.0"
        },
        "autoload": {
            "psr-4": {
                "YourNamespace\\": "src/"
            }
        },
        "extra": {
            "class": "YourNamespace\\YourPlugin"
        }
    }
    
    ```



ColorInfo Class
Overview

La classe ColorInfo fornisce un'interfaccia per manipolare e convertire colori CSS. Permette di determinare se un colore è chiaro o scuro, calcolarne la luminanza e convertirlo in vari formati.
Constructor
`__construct(string $color)`

Crea un nuovo oggetto ColorInfo basato su una stringa di colore CSS. La validazione del colore è gestita internamente e solleverà un'eccezione se il formato non è valido.
Methods
`isDark(): bool`

Determina se il colore è scuro basandosi sulla sua luminanza.
`isLight(): bool`

Determina se il colore è chiaro basandosi sulla sua luminanza.
`luminance(): float`

Calcola la luminanza relativa del colore.
`toHex(): ColorInfo`

Converte il colore nel formato esadecimale e restituisce un nuovo oggetto ColorInfo.
`toHsl(): ColorInfo`

Converte il colore nel formato HSL e restituisce un nuovo oggetto ColorInfo.
toHsla(float $alpha = 1): ColorInfo

Converte il colore nel formato HSLA con un valore alfa opzionale e restituisce un nuovo oggetto ColorInfo.
toRgb(): ColorInfo

Converte il colore nel formato RGB e restituisce un nuovo oggetto ColorInfo.
toRgba(float $alpha = 1): ColorInfo

Converte il colore nel formato RGBA con un valore alfa opzionale e restituisce un nuovo oggetto ColorInfo.
__toString(): string

Restituisce la rappresentazione del colore come stringa.
toArray(): array

Restituisce una rappresentazione dell'oggetto come array dei valori RGB.
red()

Restituisce il valore rosso del colore.
green()

Restituisce il valore verde del colore.
blue()

Restituisce il valore blu del colore.
hue(): float

Restituisce il valore hue del colore in formato HSLA.
saturation(): float

Restituisce il valore di saturazione del colore in formato HSLA.
lightness(): float

Restituisce il valore di luminosità del colore in formato HSLA.
alpha(): float

Restituisce il valore alfa del colore in formato HSLA.
Exceptions

Le eccezioni sono gestite dalla libreria ColorFactory e possono includere errori di formato del colore.


### Color Utilities Classes

#### Overview

Method `AchromaticColors::generate()` returns an array of `ColorInfoInterface` objects.
The two colors are black and white, which are the only two colors that are considered to be achromatic.
The first color is black.
The second color is white.

Method `MonochromaticColors::generate()` returns an array of `ColorInfoInterface` objects.
The colors are generated by varying the lightness of the base color.
The colors are generate passing a step value to the constructor.
Step value could be in the format of number between 0 and 1 or a percentage between 1 and 100.
The `generate()` method returns an array of `ColorInfoInterface` objects witch the middle color is the base color, the first colors uses the `tint()` method and the last colors uses the `shade()` method.
If no step value is passed to the constructor, the default value is the base color.
If you pass only one step value to the constructor you'll get three colors:

```php
// MonochromaticColors::__construct($baseColor, 50)
[
    0 => ColorInfoInterface, // Tint color
    1 => ColorInfoInterface, // Base color
    2 => ColorInfoInterface, // Shade color
]
```

If you pass two step values to the constructor you'll get five colors:

```php
// MonochromaticColors::__construct($baseColor, [25, 75])
[
    0 => ColorInfoInterface, // Tint color 25%
    1 => ColorInfoInterface, // Tint color 75%
    2 => ColorInfoInterface, // Base color
    3 => ColorInfoInterface, // Shade color 75%
    4 => ColorInfoInterface, // Shade color 25%
]
```