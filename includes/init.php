<?php

  declare(strict_types=1);

/**
  * Initialisierungsdatei für die TaskManager-Anwendung
  *
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: init.php $
  * @created $Id: 1 Mittwoch, 18. Februar 2026, 06:18:39 GMT+0200Z ztatement $
  *
  * @description Initialisierungsdatei für die TaskManager-Anwendung
  * Diese Datei wird am Anfang jeder Seite geladen und initialisiert:
  * - Session-Sicherheit
  * - Datenbank-Verbindung
  * - System-Health-Checks
  * - Sprach-System
  * - Zentrale Klassen-Instanzen
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Pufferung starten, um "Headers already sent" zu verhindern
  ob_start();

  // --- SICHERHEITS-HEADER ---
  header("X-Frame-Options: SAMEORIGIN");
  header("X-Content-Type-Options: nosniff");
  header("X-XSS-Protection: 1; mode=block");
  header("Referrer-Policy: strict-origin-when-cross-origin");
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on")
  {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
  }

  // --- CSP NONCE GENERIERUNG ---
  // Falls ein Nonce über AJAX mitgesendet wurde, verwenden wir diesen (Synchronisation)
  $cspNonce = "";
  if (
    isset($_SERVER["HTTP_X_CSP_NONCE"]) &&
    preg_match('/^[a-f0-9]{32}$/', $_SERVER["HTTP_X_CSP_NONCE"])
  ) {
    $cspNonce = $_SERVER["HTTP_X_CSP_NONCE"];
  }
  else
  {
    $cspNonce = bin2hex(random_bytes(16));
  }

  define("CSP_NONCE", $cspNonce);

  // --- CONTENT SECURITY POLICY ---
  // Ermöglicht lokale Ressourcen und benötigte CDNs.
  // Nonce wird verwendet, um inline-Skripte sicher ohne 'unsafe-inline' zu erlauben.
  $csp = "default-src 'self'; ";
  $csp .=
    "script-src 'self' 'unsafe-hashes' 'nonce-" .
    CSP_NONCE . "' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
  $csp .= "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
  $csp .= "img-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://api.qrserver.com; ";
  $csp .= "font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
  $csp .= "connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
  $csp .= "frame-ancestors 'none'; ";
  $csp .= "report-uri csp_report.php;";
  header("Content-Security-Policy: " . $csp);

  // Modernere CSP-Reporting-Methode (Report-To) für Browser, die report-to unterstützen.
  // Wir bauen eine absolute URL zum Reporting-Endpunkt, damit Report-To funktioniert.
  $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
  $reportUrl = $scheme . '://' . $host . ($basePath === '/' || $basePath === '.' ? '' : $basePath) . '/csp_report.php';
  $reportTo = json_encode([
    'group' => 'csp-endpoint',
    'max_age' => 10886400,
    'endpoints' => [['url' => $reportUrl]]
  ]);
  header('Report-To: ' . $reportTo);
  header('NEL: {"report_to":"csp-endpoint","max_age":10886400}');

  require_once __DIR__ . "/autoload.php";

  use classes\core\TaskDatabase;
  use classes\services\SystemHealthService;
  use classes\Functions;
  use classes\system\Localization;
  use classes\user\TaskUser;

/**
  * Global verfügbare Instanzen für die IDE-Unterstützung
  * @var TaskDatabase $taskDb
  * @var TaskUser $taskUser
  * @var array $lang
  */

  // --- INSTALLATION CHECK ---
  // Leitet zur Installationsseite um, wenn die Datenbankdatei nicht existiert.
  // Die install.php selbst wird von dieser Prüfung ausgenommen, um eine Endlosschleife zu vermeiden.
  if (!file_exists(DB_FILE) && basename($_SERVER["PHP_SELF"]) !== "install.php")
  {
    header("Location: install.php");
    exit();
  }

  // --- SYSTEM HEALTH CHECK ---
  // Prüft vor dem Datenbankzugriff, ob Dateien und Rechte korrekt sind.
  $healthService = new SystemHealthService(DB_FILE);
  $dbHealthIssues = $healthService->checkDatabaseHealth();
  $extensionIssues = $healthService->checkPhpExtensions();
  $healthIssues = array_merge($dbHealthIssues, $extensionIssues);

  if (!empty($healthIssues))
  {
    http_response_code(503);
    die(
      '<div style="font-family:sans-serif; 
                    color:#721c24; 
                    background-color:#f8d7da; 
                    border:1px solid #f5c6cb;
                    border-radius:5px;
                    padding:20px; margin:20px;">
        <strong>Kritischer Systemfehler:</strong><br>
        <ul style="margin-bottom:0;">
          <li>' . implode("</li>
          <li>", $healthIssues) ."</li>
        </ul>
      </div>"
    );
  }

  // Zentrale Instanzen initialisieren
  $taskDb = new TaskDatabase();

  // --- SESSION INITIALISIERUNG ---
  // Wir laden die Lebensdauer aus den Admin-Einstellungen (Standard: 3600 Sek / 1 Std)
  $sessionLifetime = (int) $taskDb->settings->getValue("session_lifetime", 3600);
  // Wir laden den Session-Namen (Präfix) aus den Einstellungen
  $sessionName = $taskDb->settings->getValue("session_prefix", "tm_");

  if (session_status() === PHP_SESSION_NONE)
  {
    // Server-seitige Lebensdauer der Session-Daten setzen
    ini_set("session.gc_maxlifetime", (string) $sessionLifetime);

    // Eindeutigen Session-Namen (Cookie-Name) festlegen
    session_name($sessionName);

    // Client-seitige Lebensdauer des Session-Cookies setzen
    session_set_cookie_params([
      "lifetime" => $sessionLifetime,
      "path" => "/",
      "secure" => isset($_SERVER["HTTPS"]),
      "httponly" => true,
      "samesite" => "Lax",
    ]);
    session_start();
  }

  $taskUser = new TaskUser($taskDb);

  // Functions-Klasse mit SettingsService initialisieren
  Functions::init($taskDb->settings);

  // --- SPRACHE LADEN (Zentral über Localization Klasse) ---
  // Initialisiert die Sprache basierend auf Session, User-DB oder Browser-Einstellung
  $lang = Localization::init($taskDb, $taskUser);
