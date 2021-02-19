# Version 17.0.0

## Bugfixes

* Fixed import-cli-simple#258
* Fixed #PAC-280: Extension library requires `etc` configuration directory
* Fixed #PAC-206: Prevent finder mappings of different libraries to be overwritten
* Fixed #PAC-318: Serialization exception when using a custom database configuration

## Features

* Add #PAC-89: Add debug email command + DebugSendPlugin
* Add #PAC-57: Deleting dedicated attribute values via import by setting a configurable value
* Add #PAC-349: Support for JMS serializer v2.0 + v3.0

# Version 16.1.0

## Bugfixes

* None

## Features

* Add functionality to also parse JSON configuration in extension libraries

# Version 16.0.2

## Bugfixes

* None

## Features

* Remove default log level initialization to `info` from configuration

# Version 16.0.1

## Bugfixes

* Fixed invald annotation on property Alias::$id

## Features

* None

# Version 16.0.0

## Bugfixes

* None

## Features

* Add #PAC-85
* Add techdivision/import-cli-simple#244
* Switch to latest techdivision/import 16.* version as dependency
* Add new method FileResolver::hasPrefix() to query whethr or not the file resolver has the default prefix
* Add functionality to also parse configuration files that'll be available in configured additional vendor directory

# Version 15.0.4

## Bugfixes

* None

## Features

* Replace composer dependency doctrine/dbal with doctrine/collections

# Version 15.0.3

## Bugfixes

* Fixed techdivision/import-configuration-jms#33

## Features

* None

# Version 15.0.2

## Bugfixes

* Fixed PHPUnit tests

## Features

* None

# Version 15.0.1

## Bugfixes

* None

## Features

* Switch to jms/serializer ^1.0 and remove unnecessary Serializer implementation

# Version 15.0.0

## Bugfixes

* Fixed techdivision/import-cli-simple#224

## Features

* Add techdivision/import#163
* Add techdivision/import-cli-simple#216
* Add techdivision/import-configuration-jms#25
* Switch to latest techdivision/import 15.* version as dependency

# Version 14.1.0

## Bugfixes

* None

## Features

* Lower dependency for jms/serializer to ^0.16.0 for compatility with Magento 2.3.3
* Add  custom implementations for SerializerBuilder and Serializer to provide forward compatibility

# Version 14.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 14.* version as dependency

# Version 13.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 13.* version as dependency

# Version 12.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 12.* version as dependency

# Version 11.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 11.* version as dependency

# Version 10.0.1

## Bugfixes

* Remove local repository registration

## Features

* None

# Version 10.0.0

## Bugfixes

* None

## Features

* Add cache configuration and alias mapping
* Switch to latest techdivision/import 10.0.* version as dependency

# Version 9.0.0

## Bugfixes

* None

## Features

* Add --cache-enabled option
* Refactoring for plugin and subject listeners
* Switch to latest techdivision/import 9.0.* version as dependency

# Version 8.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 8.0.* version as dependency

# Version 7.0.0

## Bugfixes

* None

## Features

* Extract listener configuration into an trait to make it reusable
* Extend configuration for Redis specific database configuration
* Switch to latest techdivision/import 7.0.* version as dependency

# Version 6.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 6.0.* version as dependency

# Version 5.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 5.0.* version as dependency

# Version 4.0.0

## Bugfixes

* None

## Features

* Add FileResolver configuration
* Add ParamsTrait to Configuration also
* Add DateConverter + Number Converter configuration
* Move CSV configuration from subject to CsvTrait

# Version 3.0.0

## Bugfixes

* None

## Features

* Switch to techdivision/import version ~3.0

# Version 2.0.1

## Bugfixes

* Fixed invalid JMS (un-)serialization name for Subject::$createImportedFile property

## Features

* None

# Version 2.0.0

## Bugfixes

* None

## Features

* Add SubjectConfiguration::isCreatingImportedFile() method to return create-imported-file configuration flag

# Version 1.0.0

## Bugfixes

* None

## Features

* Move PHPUnit test from tests to tests/unit folder for integration test compatibility reasons

# Version 1.0.0-beta21

## Bugfixes

* None

## Features

* Add configuration for single transaction flag

# Version 1.0.0-beta20

## Bugfixes

* None

## Features

* Add configuration for event listeners

# Version 1.0.0-beta19

## Bugfixes

* None

## Features

* Refactored DI + switch to new SqlStatementRepositories instead of SqlStatements

# Version 1.0.0-beta18

## Bugfixes

* None

## Features

* Move getHeaderMappings() + getImageTypes() methods from Subject to Configuration class

# Version 1.0.0-beta17

## Bugfixes

* None

## Features

* Implement Subject::getHeaderMappings() + Subject::getImageTypes() methods

# Version 1.0.0-beta16

## Bugfixes

* None

## Features

* Add filesystem adapter configuration

# Version 1.0.0-beta15

## Bugfixes

* Merge constructor params for logger initialization by name instead of using order

## Features

* None

# Version 1.0.0-beta14

## Bugfixes

* None

## Features

* Replace array configuration nodes with ArrayCollection types

# Version 1.0.0-beta13

## Bugfixes

* Update PHPUnit test to also support loading library JMS in library context

## Features

* None

# Version 1.0.0-beta12

## Bugfixes

* Fixed invalid array + ArrayCollection initialization

## Features

* None

# Version 1.0.0-beta11

## Bugfixes

* None

## Features

* Add configuration implementations for import/export adapters

# Version 1.0.0-beta10

## Bugfixes

* None

## Features

* Refactory ConfigurationFactory::factory() method from static non-static
* ConfigurationFactory now implements ConfigurationFactoryInterface

# Version 1.0.0-beta9

## Bugfixes

* None

## Features

* Use Robo for Travis-CI build process

# Version 1.0.0-beta8

## Bugfixes

* None

## Features

* Add setArchiveArtefacts() method to Configuration class

# Version 1.0.0-beta7

## Bugfixes

* None

## Features

* Add setArchiveDir() method

# Version 1.0.0-beta6

## Bugfixes

* None

## Features

* Add countDatabases() + setExtensionLibraries() method to Configuration class

# Version 1.0.0-beta5

## Bugfixes

* None

## Features

* Add system-name configuration option

# Version 1.0.0-beta4

## Bugfixes

* None

## Features

* Plugin configuration now supports params by using ParamsTrait

# Version 1.0.0-beta3

## Bugfixes

* None

## Features

* Add getMultipleValueDelimiter() method to Subject implementation also

# Version 1.0.0-beta2

## Bugfixes

* None

## Features

* Add getMultipleValueDelimiter() method to Configuration implementation

# Version 1.0.0-beta1

## Bugfixes

* None

## Features

* Integrate Symfony DI functionality

# Version 1.0.0-alpha1

## Bugfixes

* None

## Features

* Initial Release