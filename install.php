<?php
/**
  * 
  * TaskManager - ein einfaches und leichtes PHP Aufgaben-Management System auf Basis von PHP und SQLite
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: install.php 1 Mittwoch, 18. März 2026, 05:57:51 GMT+0200Z ztatement $
  *
  * @link https://github.com/ztatement/taskmanager
  *
  * @license MIT
  *
  * @category Installation
  * @package TaskManager
  *
  * @description Installationsseite für TaskManager
  *
  */

  declare(strict_types=1);

  // Pufferung starten
  ob_start();

  // Autoloader und Konfiguration laden
  require_once __DIR__ . '/includes/autoload.php';

  use classes\services\InstallService;
  use classes\services\SystemHealthService;
  use classes\security\CsrfSecurity;
  use classes\system\Localization;

  // Wenn die Datenbank bereits existiert, zur Startseite umleiten.
  if (file_exists(DB_FILE))
  {
    header('Location: index.php');
    exit();
  }

  // --- SPRACHE LADEN ---
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
  // --- ENDE SPRACHE LADEN ---

  $error = '';
  $success = false;
  $healthIssues = [];
  $showForm = false;
  $csrf = new CsrfSecurity();

  // 1. Immer die System-Voraussetzungen prüfen
  $healthService = new SystemHealthService(DB_FILE);
  $dbHealthIssues = $healthService->checkDatabaseHealth();
  $extensionIssues = $healthService->checkPhpExtensions();
  $healthIssues = array_merge($dbHealthIssues, $extensionIssues);

  $dbDir = dirname(DB_FILE);
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
    $showForm = true;
  }

  // 2. Installationsprozess starten, wenn das Formular gesendet wird
  if ($showForm && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_install']))
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

?>
  <!DOCTYPE html>
  <html lang="de" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css">
  </head>
  <body class="d-flex justify-content-center align-items-center min-vh-100 bg-dark-subtle">
    <div class="card shadow-lg" style="width: 100%; max-width: 600px;">
      <div class="card-body p-5">
        <h2 class="card-title text-center mb-4"><i class="fas fa-cogs me-2"></i> TaskManager Installation</h2>

        <?php if ($success): ?>
        <div class="alert alert-success">
          <h4 class="alert-heading">Installation erfolgreich!</h4>
          <p>Die Datenbank und der Administrator-Account wurden erfolgreich erstellt.</p>
          <hr>
          <p class="mb-0">Sie können sich nun mit den von Ihnen gewählten Daten anmelden.</p>
          <div class="d-grid mt-4">
            <a href="login.php" class="btn btn-primary">Zur Login-Seite</a>
          </div>
        </div>
        <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger">
          <strong>Fehler bei der Installation:</strong><br>
          <?= $error ?>
        </div>
        <div class="d-grid">
          <a href="install.php" class="btn btn-secondary">Erneut versuchen</a>
        </div>
        <?php elseif ($showForm): ?>
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
    <script src="static/js/utils.js"></script>
    <script src="static/js/register.js"></script>
  </body>
  </html>