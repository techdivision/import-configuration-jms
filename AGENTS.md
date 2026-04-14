# AGENTS.md - import-configuration-jms

## Zweck & Verantwortung

Das `import-configuration-jms` Modul bietet eine **JMS-basierte Konfiguration-Implementierung** für das Pacemaker Import-System. Es ist ein **Tier 2 Modul** und implementiert die Interfaces aus `import-configuration`.

**Hauptverantwortung:**
- JMS-Serialisierung für Import-Konfiguration
- Parsing von Konfigurationsdateien (XML, YAML)
- Event-Driven Konfiguration-Verarbeitung
- Expression Language Support für dynamische Konfiguration

## Architektur & Design Patterns

### Implementierungen
- **Configuration**: Haupt-Konfiguration-Klasse
- **ConfigurationParserFactory**: Factory für Parser-Erstellung
- **ConfigurationParserInterface**: Basis-Interface für Parser
- **JmsConfigurationParser**: JMS-basierter Parser

### Verwendete Patterns
- **Factory Pattern**: Für Parser-Erstellung
- **Strategy Pattern**: Verschiedene Parser-Strategien
- **Event-Driven**: Nutzt `league/event` für Hooks
- **Builder Pattern**: Für Konfiguration-Erstellung

### Externe Dependencies
- **jms/serializer** - JMS-Serialisierung
- **doctrine/collections** - Collections
- **doctrine/annotations** - Annotations
- **symfony/expression-language** - Expression Language
- **jean85/pretty-package-versions** - Version-Management

## Abhängigkeiten

### Externe Pakete
- **jms/serializer** ^3.0 - JMS-Serialisierung
- **doctrine/collections** ^1.0|^2.0 - Collections
- **doctrine/annotations** ^1.0 - Annotations
- **symfony/expression-language** ^4.0|^5.0|^6.0 - Expression Language
- **jean85/pretty-package-versions** ^1.0|^2.0 - Version-Management

### TechDivision Dependencies
- **import** ^18.1 - Core Framework
- **import-dbal** ^2.0 - DBAL-Interfaces

### Abhängig von diesem Modul (2 Reverse Dependencies)
1. **import-cli** - CLI nutzt JMS-Konfiguration
2. **import-cli-simple** - Master CLI nutzt JMS-Konfiguration

## Wichtige Entry Points

### Konfiguration Klassen
```php
// Configuration
Configuration::getOperations(): array
Configuration::getOperation($name): OperationConfigurationInterface
Configuration::getPlugins(): array

// Configuration Parser Factory
ConfigurationParserFactory::create($type): ConfigurationParserInterface

// JMS Configuration Parser
JmsConfigurationParser::parse($file): Configuration
```

### Verwendungsbeispiel
```php
// In CLI
$factory = new ConfigurationParserFactory();
$parser = $factory->create('jms');
$configuration = $parser->parse('config.xml');
$operations = $configuration->getOperations();
```

## Events & Extension Points

### Listeners
- **ConfigurationLoadedListener**: Nach Konfiguration-Laden
- **OperationConfiguredListener**: Nach Operation-Konfiguration
- **PluginConfiguredListener**: Nach Plugin-Konfiguration

### Event-Registrierung
```php
// In Konfiguration
$eventManager->addListener('configuration.loaded', new CustomListener());
```

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 2 Modul**: Erweitert Tier 1 mit JMS-Serialisierung
2. **JMS-fokussiert**: Nutzt JMS für Serialisierung
3. **Expression Language**: Unterstützt dynamische Konfiguration
4. **Event-Driven**: Für Konfiguration-Hooks

### Bei Änderungen
- **JMS-Kompatibilität**: Beachte JMS-Serialisierung
- **Expression Language**: Beachte Syntax bei Änderungen
- **Backward Compatibility**: Alte Konfigurationen sollten noch funktionieren

### Implementierungs-Hinweise
- Nutze JMS-Annotations für Serialisierung
- Beachte Expression Language Syntax
- Erwäge Konfiguration-Validierung

## Häufige Use Cases

### XML-Konfiguration-Beispiel
```xml
<configuration>
  <operations>
    <operation name="product">
      <plugins>
        <plugin name="validator" class="\Validator" />
        <plugin name="price-converter" class="\PriceConverter" />
      </plugins>
    </operation>
  </operations>
</configuration>
```

### Expression Language Beispiele
```php
// In Konfiguration
locale: "${env('SHOP_LOCALE', 'en_US')}"
batch_size: "${env('BATCH_SIZE', 1000)}"
```

## Performance-Überlegungen

- **Parse-Time**: XML-Parsing: ~50-100ms für Standard Config, ~200-500ms für große (>1MB)
- **JMS-Overhead**: JMS-Serialisierung ist ~5-10% langsamer als native PHP Arrays
- **Caching**: Config wird nach Parse gecacht - nur einmalige Parse pro Process
- **Expression-Language**: EL-Evaluation kostet ~1-2ms pro Expression
- **Optimal für**: < 50 Operations, < 200 Plugins in einer Config

## Verwandte Module

- **import-configuration**: Definiert Interfaces die dieses Modul implementiert
- **import-cli**: Nutzt diese JMS-Implementation für CLI
- **import**: Core Framework nutzt Konfiguration
- **import-configuration-jms** ← **diese Datei** (JMS Implementation!)

## Troubleshooting & FAQ

**Q: XML-Parsing schlägt fehl**
- A: XML-Schema prüfen! JMS nutzt Doctrine-Annotations. DTD/XSD nicht immer nötig aber hilfreich.

**Q: Expression Language funktioniert nicht**
- A: Nicht alle PHP-Funktionen verfügbar! Nur standardisierte EL-Funktionen (strlen, substr, etc).

**Q: Config-Parser sehr langsam**
- A: Große Configs (>10MB) können langsam sein. Config splitten oder Caching prüfen.

**Q: "Syntax error in expression" mit Umgebungsvariablen**
- A: EL-Syntax: `${env('NAME')}` oder `${env('NAME', 'default')}`. Quote-Escaping beachten.

## Bekannte Einschränkungen

- **JMS-Only**: Nur JMS-Serialisierung unterstützt
- **Keine Konfiguration-Validierung**: Validierung erfolgt in Importern
- **Expression Language Limits**: Nicht alle PHP-Funktionen verfügbar
- **Performance**: JMS-Parsing kann bei großen Konfigurationen langsam sein

## Zusammenfassung

`import-configuration-jms` ist ein **Tier 2 Modul**, das JMS-basierte Konfiguration für das Pacemaker-System implementiert. Es ist zentral für die Konfiguration von Import-Operationen und unterstützt Expression Language für dynamische Konfiguration.

**Für Agenten:** Verstehe dieses Modul als **JMS-Konfiguration-Implementierung** mit Expression Language Support.
