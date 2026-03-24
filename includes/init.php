<?php
/**
  * Initialisierungsdatei für die TaskManager-Anwendung
  * 
  * Diese Datei wird am Anfang jeder Seite geladen und initialisiert:
  * - Session-Sicherheit
  * - Datenbank-Verbindung
  * - System-Health-Checks
  * - Sprach-System
  * - Zentrale Klassen-Instanzen
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: init.php 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  *
  * @category Initialization
  * @package TaskManager
  */

  declare(strict_types=1);

  // Pufferung starten, um "Headers already sent" zu verhindern
  ob_start();

  require_once __DIR__ . '/autoload.php';

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

  // Session sicher starten
  if (session_status() === PHP_SESSION_NONE)
  {
    session_start();
  }

  // --- INSTALLATION CHECK ---
  // Leitet zur Installationsseite um, wenn die Datenbankdatei nicht existiert.
  // Die install.php selbst wird von dieser Prüfung ausgenommen, um eine Endlosschleife zu vermeiden.
  if (!file_exists(DB_FILE) && basename($_SERVER['PHP_SELF']) !== 'install.php')
  {
    header('Location: install.php');
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
    die
    ('
      <div style="font-family:sans-serif; color:#721c24; background-color:#f8d7da; border:1px solid #f5c6cb; padding:20px; margin:20px; border-radius:5px;">
        <strong>Kritischer Systemfehler:</strong><br>
        <ul style="margin-bottom:0;">
          <li>' . implode('</li><li>', $healthIssues) . '</li>
        </ul>
      </div>
    ');
  }

  // Zentrale Instanzen initialisieren
  $taskDb = new TaskDatabase();
  $taskUser = new TaskUser($taskDb);

  // Functions-Klasse mit SettingsService initialisieren
  Functions::init($taskDb->settings);

  // --- SPRACHE LADEN (Zentral über Localization Klasse) ---
  // Initialisiert die Sprache basierend auf Session, User-DB oder Browser-Einstellung
  $lang = Localization::init($taskDb, $taskUser);
