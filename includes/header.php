<?php
/**
  * Header für TaskManager
  *
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: header.php $
  * @created $Id: 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @description Header für den Aufgaben-Manager
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Sicherstellen, dass init geladen ist (falls header einzeln eingebunden wird)
  if (!isset($taskDb))
  {
    require_once __DIR__ . '/init.php';
  }

  // Fallback: Falls $lang nicht existiert (z.B. wenn init.php nicht geladen wurde), Standard laden
  if (!isset($lang))
  {
    require_once __DIR__ . '/config.php';
    if (file_exists(LANGUAGE_PATH . 'german_de-DE.lang.php'))
    {
      require_once LANGUAGE_PATH . 'german_de-DE.lang.php';
    }
  }

  $taskUser->requireLogin();

  // Dynamischen Seitentitel basierend auf der aktuellen Datei setzen
  $pageTitle = "";
  $scriptName = basename($_SERVER["SCRIPT_NAME"]);

  switch ($scriptName)
  {
    case "index.php":
      $pageTitle = $lang["menu_home"] ?? "Startseite";
      break;
    case "task.php":
      $pageTitle = $lang["menu_task_entry"] ?? "Erfassung";
      break;
    case "tasks_list.php":
      $pageTitle = $lang["menu_all_tasks"] ?? "Alle Aufgaben";
      break;
    case "stats.php":
      $pageTitle = $lang["menu_stats"] ?? "Statistik";
      break;
    case "templates.php":
      $pageTitle = $lang["menu_templates"] ?? "Vorlagen";
      break;
    case "day_messages.php":
      $pageTitle = $lang["menu_day_messages"] ?? "Tagesnotiz";
      break;
    case "profile.php":
      $pageTitle = $lang["menu_profile"] ?? "Mein Profil";
      break;
    case "admin.php":
      $pageTitle = $lang["menu_admin"] ?? "Admin";
      if (isset($_GET["section"]))
      {
        $sectionKey = "admin_menu_" . str_replace("-", "_", $_GET["section"] ?? '');
        if (isset($lang[$sectionKey]))
        {
          $pageTitle = $lang[$sectionKey] . " - " . $pageTitle;
        }
      }
      break;
    case "edit_task_user.php":
      $pageTitle = $lang["admin_headline_edit_user"] ?? "Benutzer bearbeiten";
      break;
  }

  // Headers, um Caching zu verhindern.
  // Das sorgt dafür, dass nach dem Logout beim Klick auf "Zurück" im Browser
  // die Seite nicht aus dem Cache geladen wird, sondern neu angefragt werden muss.
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Pragma: no-cache");
  header("Expires: 0");

  // Wartungsmodus prüfen
  $maintenanceMode = $taskDb->settings->getMaintenanceMode();
  if ($maintenanceMode["enabled"] && !$taskUser->isAdmin())
  {
    header("Location: maintenance.php");
    exit();
  }

  $userId = $taskUser->getUserId();

?>
<!DOCTYPE html>
<html lang="<?=$lang['language'];?>" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php if (isset($csrf) && $csrf instanceof \classes\security\CsrfSecurity): ?>
  <meta name="csrf-token" content="<?= $csrf->getToken() ?>">
  <?php endif; ?>

  <title><?= (!empty($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '') . ($lang['app_name'] ?? 'TaskManager') ?></title>

  <link href="<?= BOOTSTRAP_CSS ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?= FONT_AWESOME_CSS ?>">
  <link rel="stylesheet" href="<?= CSS_URL ?>main.css">
  <link rel="stylesheet" href="<?= SWEETALERT_CSS ?>">

  <script nonce="<?= CSP_NONCE ?>" src="<?= SWEETALERT_JS ?>"></script>
  <script nonce="<?= CSP_NONCE ?>" src="<?= JS_URL ?>utils.js?v=<?= file_exists(STATIC_PATH . 'js/utils.js') ? filemtime(STATIC_PATH . 'js/utils.js') : '1' ?>"></script>
  <script nonce="<?= CSP_NONCE ?>">
    <?php if (!isset($csrf)) { $csrf = new \classes\security\CsrfSecurity(); } ?>
    // Zentrales Konfigurationsobjekt für JavaScript (CSP-konform)
    window.taskConfig = {
      csrfToken: '<?= $csrf->getToken() ?>',
      cspNonce: '<?= CSP_NONCE ?>',
      username: '<?= htmlspecialchars($_SESSION['task_username'] ?? 'Gast') ?>',
      userId: '<?= $taskUser->getUserId() ?>',
      role: '<?= $taskUser->getRole() ?>',
      basePath: '<?= $base_path ?? "" ?>',
      timerLimit: <?= (int)$taskDb->settings->getValue('timer_persistence_limit', 3600) ?>,
      // Globale SweetAlert2 Konfiguration (CSP-optimiert)
      swalConfig: {
        nonce: '<?= CSP_NONCE ?>',
        buttonsStyling: false,
        customClass: {
          confirmButton: 'btn btn-primary px-4 mx-2',
          cancelButton: 'btn btn-secondary px-4 mx-2',
          denyButton: 'btn btn-danger px-4 mx-2',
          popup: 'shadow-lg border-0',
          title: 'fw-bold',
          htmlContainer: 'text-start'
        },
        background: '<?= defined("SWAL_BACKGROUND") ? SWAL_BACKGROUND : "#212529" ?>',
        color: '<?= defined("SWAL_COLOR") ? SWAL_COLOR : "#f8f9fa" ?>',
      }
    };
    if (typeof Swal !== 'undefined') {
        window.Swal = Swal.mixin(window.taskConfig.swalConfig);
    }
    // Legacy-Support für bestehende Scripte
    var csrfToken = window.taskConfig.csrfToken;
    var currentUser = window.taskConfig.username;
    var userRole = window.taskConfig.role;
  </script>
</head>
<body>

  <?php if (isset($_SESSION['task_username']) && $_SESSION['task_username'] === 'test-admin'): ?>
    <div class="alert alert-warning py-1 mb-0 text-center rounded-0 border-0 small shadow-sm" style="z-index: 2000; position: relative;">
      <i class="fas fa-vial me-2"></i> <strong>Sandbox-Modus:</strong> Sie arbeiten auf einer Kopie. Änderungen an System-Daten oder anderen Benutzern sind isoliert.
    </div>
  <?php endif; ?>

  <?php include TEMPLATE_PATH . '/header_nav' . TEMPLATE_EXTENSION; ?>

  <div class="bd-masthead" id="content">
    <div class="container-xl">
