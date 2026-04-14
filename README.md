# TaskManager (TM)

Ein einfaches, leichtgewichtiges und modulares Aufgaben-Management-System auf Basis von PHP und SQLite. Entwickelt für die effiziente Verwaltung von Aufgaben in Teams mit verschiedenen Rollen.

## 🚀 Features

- **Rollenbasiertes System**: Unterstützung für Admin, Manager, Teamleiter, Supervisor und reguläre Benutzer.
- **Interaktive Dashboards**: Visualisierung der Team-Performance mit Chart.js.
- **Sicherheit**: Integrierter CSRF-Schutz, Passwort-Richtlinien (PwnedPasswords API Check), 2FA-Vorbereitung und Rate Limiting.
- **Wartung & Backups**: Automatisierte System- und Benutzerdatenbank-Backups, Datenbank-Optimierung (VACUUM) und Integritätsprüfungen.
- **Internationalisierung**: Mehrsprachigkeit (i18n) für das Frontend und Backend.
- **Modularität**: Service-orientierte Architektur zur einfachen Erweiterung.

## 📋 Voraussetzungen

Um den TaskManager zu betreiben, benötigst du:

- **PHP**: Version 8.0 oder höher.
- **Webserver**: Apache (mit mod_rewrite) oder Nginx.
- **Datenbank**: SQLite3.
- **PHP-Erweiterungen**:
  - `pdo_sqlite`
  - `intl` (für Lokalisierung)
  - `gd` (für Bildbearbeitung/Logos)
  - `mbstring` (Multibyte-String Support)
  - `openssl` (für Verschlüsselung)
  - `curl` (für API-Anfragen)
  - `zip` (für Backups)

## 🛠️ Installation

1. **Dateien kopieren**:
   Lade das Repository herunter und kopiere die Dateien in das Web-Verzeichnis deines Servers (z. B. `/var/www/html/taskmanager` oder `c:/wamp64/www/taskmanager`).

2. **Berechtigungen setzen**:
   Der Webserver benötigt Schreibrechte auf folgende Verzeichnisse (wichtig für SQLite und Logging):
   - `/db/` (Speicherort der SQLite-Datenbanken)
   - `/backups/` (System- und User-Backups)
   - `/.logs/` (Fehler- und Event-Logs)
   - `/uploads/` (für Anhänge oder Logos)

3. **Konfiguration**:
   - Die grundlegenden Einstellungen werden in der Datenbank gespeichert.
   - Stelle sicher, dass die `base_url` in den Systemeinstellungen korrekt gesetzt ist, damit Links und AJAX-Requests funktionieren.

4. **Erster Login**:
   - Standardmäßig wird bei der Initialisierung ein Admin-Benutzer angelegt.
   - Navigiere zu `login.php` und melde dich an.

5. **Test Login**:
   - Auf der Demo-Seite kann der Aufgaben-Manager nach Login getestet werden.
   - test-user -> 1.te-stUs-ers!
   - test-admin -> 1.te-stAd-min!

## 🔒 Sicherheitshinweise

- **SSL/HTTPS**: Es wird dringend empfohlen, die Anwendung nur über HTTPS zu betreiben.
- **Datenbank**: Schütze das `/db/` Verzeichnis vor direktem Zugriff via Browser.
  - **Apache**: Eine entsprechende `.htaccess` im `/db/`-Ordner mit `Deny from All` reicht aus.
  - **Nginx**: Da Nginx `.htaccess` ignoriert, muss folgender Block in die Server-Konfiguration eingefügt werden:
    ```nginx
    location ^~ /db/ {
        deny all;
        return 403;
    }
    ```
- **API-Keys**: Falls externe APIs (z. B. für Passwörter oder SMS) genutzt werden, hinterlege die Keys sicher im Admin-Bereich.

## 📂 Projektstruktur

- `/classes`: Enthält die Business-Logik (Repositories, Services, Core).
- `/includes`: Autoloader, Header, Footer und globale Templates.
- `/static`: CSS, JavaScript (Charts, UI-Logik) und Bilder.
- `/templates`: HTML-Templates für die verschiedenen Sektionen.

## 🧪 Entwicklung

Um den Debug-Modus zu aktivieren, kann die Einstellung `debug_mode_enabled` im Admin-Bereich unter "System" auf `true` gesetzt werden. Dies aktiviert detaillierte Fehlermeldungen via `ExceptionHandler`.

## 📄 Lizenz

Dieses Projekt ist unter der **MIT-Lizenz** lizenziert. Weitere Details findest du in der Datei LICENSE.

---
**Autor**: Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
**Version**: 1.1.0.2026.04.10

## 📝 TODO / Roadmap

- [ ] **Zeitstempel-Cleanup (DayMessageRepository)**: Sobald die Migration in der Admin-Oberfläche durchgeführt wurde und keine alten Backups mit String-Datum mehr eingespielt werden müssen, kann die `CASE WHEN` Logik im Repository entfernt werden. 
  - *Ziel*: Umstellung auf rein numerische `unixepoch` Abfragen für maximale SQLite-Performance.
  - *Datei*: `classes/repositories/DayMessageRepository.php`
```
