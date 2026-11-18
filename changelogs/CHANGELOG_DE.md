# Initiale Veröffentlichung

## Version 1.0.0

### Funktionen

- Produktsuche nach Name oder Artikelnummer direkt auf der Warenkorbseite und im Offcanvas-Warenkorb.
- Suchvorschläge mit Produktname, Artikelnummer, Mengenauswahl und direkter Möglichkeit zum Hinzufügen zum Warenkorb.
- Hinzufügen einfacher Produkte aus dem Offcanvas ohne Verlassen der aktuellen Storefront-Seite.
- Varianten-Auswahl in einem Dialog mit dem Shopware-Kaufwidget.
- Unabhängige Aktivierung der Suche für Warenkorbseite und Offcanvas über die Plugin-Konfiguration.
- Steuerung der Verfügbarkeit über eine Shopware-Rule-Builder-Regel.
- Optionale Warenkorb-Icon-Schaltflächen mit zugänglicher Beschriftung für einfache Produkte in Suchvorschlägen.
- Deutsche und englische Storefront-Textbausteine und Konfigurationsbeschriftungen.
- Unterstützung für Shopware 6.7.x und PHP 8.2 bis 8.5 im Rahmen der Anforderungen der installierten Shopware-Version.

### Qualitätssicherung und Entwicklung

- PHP-Unit-Tests für Verfügbarkeitsprüfung, Twig-Erweiterung, Controller sowie Service- und Routing-Konfiguration mit verbindlicher 100-%-Zeilenabdeckung.
- JavaScript-Unit-Tests mit Jest für Plugin-Registrierung, Ereignisverwaltung, Modal-Bereinigung und Initialisierung der Suchvorschläge; verbindliche 100-%-Abdeckung von Statements, Verzweigungen, Funktionen und Zeilen.
- Playwright-E2E-Tests auf Basis der Shopware Acceptance Test Suite für Suche, Warenkorb-Aktionen, Konfiguration, Verfügbarkeitsregeln und Varianten.
- CI-Pipeline mit PHP- und JavaScript-Tests, Coverage-Prüfung, TypeScript-Prüfung und Playwright-Ausführung gegen eine frisch installierte Shopware-Instanz.
- Shopware-Extension-Validierung gegen die niedrigste und höchste unterstützte Version sowie Bereitstellung von Coverage- und Testberichten als CI-Artefakte.
- Automatische Release-Tags anhand der Composer-Version und vorbereitete Packagist-Aktualisierung nach Einrichtung der erforderlichen Zugangsdaten.
- Docker-Compose-Umgebung für die lokale Entwicklung.

