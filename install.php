<?php

   declare(strict_types=1);

/**
  * TaskManager - Installations- und Wartungsskript
  *
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: install.php $
  * @created $Id: 1 Mittwoch, 18. März 2026, 05:57:51 GMT+0200Z ztatement $
  *
  * @description Installations- und Wartungsskript
  * Dieses Skript dient der Ersteinrichtung des Systems sowie der manuellen 
  * Aktualisierung der Datenbankstrukturen bei Versionssprüngen.
  *
  * Kernfunktionen:
  * - Validierung der Systemvoraussetzungen (PHP-Extensions, Schreibrechte)
  * - Erstellung der zentralen System-Datenbank (SQLite)
  * - Anlage des ersten Administrator-Accounts
  * - Migration bestehender Benutzer-Datenbanken auf neue Schemata
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  ob_start();

  require_once __DIR__ . '/includes/autoload.php';

  // Namespaces importieren
  use classes\core\DatabaseConnection;
  use classes\services\InstallService;
  use classes\services\SystemHealthService;
  use classes\security\CsrfSecurity;
  use classes\system\SystemInitializer;
  use classes\core\TaskDatabase;
  use classes\system\Localization;

  // Sicherheits-Check: Wenn das System bereits installiert ist, wird der Zugriff auf 
  // die Installations-Maske gesperrt und auf das Dashboard umgeleitet.
  if (file_exists(DB_FILE))
  {
    header('Location: index.php');
    exit();
  }

  if (session_status() === PHP_SESSION_NONE)
  {
    session_start();
  }
  if (isset($_GET['lang']))
  {
    $availableLanguages = Localization::getAvailableLanguages();
    if (array_key_exists($_GET['lang'], $availableLanguages))
    {
      $_SESSION['public_lang'] = $_GET['lang'];
    }
    $queryParams = $_GET;
    unset($queryParams['lang']);
    $redirectUrl = 'install.php' . (!empty($queryParams) ? '?' . http_build_query($queryParams) : '');
    header('Location: ' . $redirectUrl);
    exit();
  }

/**
  * Sprachsteuerung: Ermittelt die zu verwendende Sprache (Session -> Browser-Erkennung -> Default)
  */
  $currentLang = $_SESSION['public_lang'] ?? Localization::detectBrowserLanguage() ?? 'german_de-DE';
  $langFile = LANGUAGE_PATH . $currentLang . '.lang.php';
  if (file_exists($langFile))
  {
    require_once $langFile;
  }
  else
  {
    require_once LANGUAGE_PATH . 'german_de-DE.lang.php';
  }

  // Initialisierung der Zustandsvariablen
  $error = '';
  $success = false;
  $isInstalled = file_exists(DB_FILE);
  $healthIssues = [];
  $showForm = false;
  $csrf = new CsrfSecurity();

/**
  * 1. System-Diagnose
  * Prüft Schreibrechte im Verzeichnis und ob alle notwendigen PHP-Module (PDO, SQLite, etc.) geladen sind.
  */
  $healthService = new SystemHealthService(DB_FILE);
  $dbHealthIssues = $healthService->checkDatabaseHealth();
  $extensionIssues = $healthService->checkPhpExtensions();
  $healthIssues = array_merge($dbHealthIssues, $extensionIssues);

  // Zusätzliche explizite Prüfung des Datenbankverzeichnisses
  $dbDir = DB_FILE_PATH;
  if (!is_dir($dbDir) || !is_writable($dbDir))
  {
    $healthIssues[] = "Das Datenbank-Verzeichnis ist nicht beschreibbar: " . htmlspecialchars($dbDir);
  }

  if (!empty($healthIssues))
  {
    $error = '<ul><li>' . implode('</li><li>', $healthIssues) . '</li></ul>';
    $showForm = false;
  }
  else
  {
    $showForm = true; // System bereit zur Installation oder zum Update
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
  /**
    * FALL A: Datenbank-Struktur aktualisieren
    * Wird aufgerufen, wenn das System bereits existiert und der User den Update-Button klickt.
    */
    if (isset($_POST['update_db_structure']))
    {
      if (!$csrf->validateToken($_POST['csrf_token'] ?? ''))
      {
        $error = $lang['admin_error_csrf'] ?? 'Ungültiger Sicherheitstoken. Bitte versuchen Sie es erneut.';
      }
      else
      {
        try
        {
          // Initialisiert/Migriert die Systemtabellen (settings, users, chat, etc.)
          new SystemInitializer(DB_FILE);

          // Aktualisiert alle individuellen Benutzer-Datenbanken (Templates & Tasks)
          $taskDb = new TaskDatabase();
          $allUsers = $taskDb->users->getAllTaskUsers();
          $currentYear = (int)date('Y');
          
          foreach ($allUsers as $user)
          {
            // Der bloße Verbindungsaufbau via DatabaseConnection triggert die internen 'CREATE TABLE IF NOT EXISTS' Logiken.
            DatabaseConnection::userData(DB_FILE_PATH, $user['username']);
            DatabaseConnection::userTasks(DB_FILE_PATH, $user['username'], $currentYear);
          }
          $success = $lang['install_success_db_updated'] ?? 'Datenbankstruktur erfolgreich aktualisiert.';
        }
        catch (Exception $e)
        {
          $error = sprintf($lang['install_error_db_update'] ?? 'Fehler beim Datenbank-Update: %s', $e->getMessage());
        }
      }
    }
  /**
    * FALL B: Erstinstallation
    * Erstellt die System-DB und legt den ersten Admin-Account an.
    */
    elseif (!$isInstalled && $showForm && isset($_POST['start_install']))
    {
      $installService = new InstallService(DB_FILE, $csrf);
      $result = $installService->handleInstallRequest($_POST);

      if (isset($result['success']) && $result['success'])
      {
        $success = true;
        $showForm = false;
      }
      elseif (isset($result['error']))
      {
        $error = $result['error'];
      }
      else
      {
        $error = 'Unbekannter Fehler.';
      }
    }
  }

?>
  <!DOCTYPE html>
  <html lang="de" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager Installation</title>
    <link href="<?= BOOTSTRAP_CSS ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= FONT_AWESOME_CSS ?>">
    <link rel="stylesheet" href="<?= FLAG_CSS ?>">
  </head>
  <body class="d-flex justify-content-center align-items-center min-vh-100 bg-dark-subtle">
    <div class="card shadow-lg" style="width: 100%; max-width: 600px;">
      <div class="card-body p-5">
        <h2 class="card-title text-center mb-4"><i class="fas fa-cogs me-2"></i> TaskManager Installation</h2>

        <?php if ($success && !$isInstalled): // Erfolgreiche Erstinstallation ?>
          <div class="alert alert-success">
            <h4 class="alert-heading">Installation erfolgreich!</h4>
            <p>Die Datenbank und der Administrator-Account wurden erfolgreich erstellt.</p>
            <hr>
            <p class="mb-0">Sie können sich nun mit den von Ihnen gewählten Daten anmelden.</p>
            <div class="d-grid mt-4">
              <a href="login.php" class="btn btn-primary">Zur Login-Seite</a>
            </div>
          </div>
        <?php elseif ($success && $isInstalled): // Erfolgreiches Update ?>
          <div class="alert alert-success">
            <h4 class="alert-heading">Update erfolgreich!</h4>
            <p><?= htmlspecialchars($success) ?></p>
            <div class="d-grid mt-4">
              <a href="index.php" class="btn btn-primary">Zum Dashboard</a>
            </div>
          </div>
        <?php elseif ($isInstalled && empty($error)): // System ist installiert, zeige Update-Option ?>
          <div class="alert alert-info">
            <h4 class="alert-heading"><?= $lang['install_system_already_installed'] ?? 'Das System ist bereits installiert.' ?></h4>
            <p>Sie können die Datenbankstruktur auf den neuesten Stand bringen.</p>
            <p class="mb-0">Dies ist nützlich nach einem Update der Anwendungsdateien, um neue Tabellen oder Spalten hinzuzufügen.</p>
            <form method="post" class="mt-3">
              <input type="hidden" name="csrf_token" value="<?= $csrf->getToken() ?>">
              <button type="submit" name="update_db_structure" class="btn btn-primary">
                <i class="fas fa-sync me-2"></i> <?= $lang['install_button_update_db'] ?? 'Datenbankstruktur aktualisieren' ?>
              </button>
            </form>
          </div>
        <?php elseif (!empty($error)): ?>
          <div class="alert alert-danger">
            <strong>Fehler bei der Installation:</strong><br>
            <?= $error ?>
          </div>
          <div class="d-grid">
            <a href="install.php" class="btn btn-secondary">Erneut versuchen</a>
          </div>
        <?php elseif (!$isInstalled && $showForm): // Normales Installationsformular ?>
          <p class="text-center">Willkommen! Alle Systemvoraussetzungen sind erfüllt. Bitte erstellen Sie den ersten Administrator-Account, um die Installation abzuschließen.</p>
          <form method="post" class="mt-4">
            <input type="hidden" name="csrf_token" value="<?= $csrf->getToken() ?>">
            <div class="mb-3">
              <label for="admin_user" class="form-label">Admin-Benutzername</label>
              <input type="text" class="form-control" id="admin_user" name="admin_user" required>
            </div>
            <div class="mb-3">
              <label for="admin_email" class="form-label">Admin-E-Mail</label>
              <input type="email" class="form-control" id="admin_email" name="admin_email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Admin-Passwort</label>
              <input type="password" class="form-control" id="password" name="admin_pass" required>
              <div class="progress mt-2" style="height: 5px;">
                <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <small id="password-strength-text" class="form-text"></small>
            </div>
            <div class="d-grid">
              <button type="submit" name="start_install" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket me-2"></i> Installation abschließen
              </button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
    <script src="<?= JS_URL ?>utils.js"></script>
    <script src="<?= JS_URL ?>register.js"></script>
  </body>
  </html>