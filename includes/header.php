<?php
/**
  * Header für TaskManager
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: header.php 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Header
  * @package TaskManager
  * 
  * @description Header für TaskManager
  */

  // Sicherstellen, dass init geladen ist (falls header einzeln eingebunden wird)
  if (!isset($taskDb))
  {
    require_once __DIR__ . '/init.php';
  }

  // Fallback: Falls $lang nicht existiert (z.B. wenn init.php nicht geladen wurde), Standard laden
  if (!isset($lang))
  {
    if (file_exists(__DIR__ . '/../static/languages/german_de-DE.lang.php'))
    {
      require_once __DIR__ . '/../static/languages/german_de-DE.lang.php';
    }
  }

  $taskUser->requireLogin();

  // Dynamischen Seitentitel basierend auf der aktuellen Datei setzen
  $pageTitle = "";
  $scriptName = basename($_SERVER["SCRIPT_NAME"]);

  switch ($scriptName) {
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
    <title><?= (!empty($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '') . ($lang['app_name'] ?? 'TaskManager') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./static/css/_slug_.css">
    <link rel="stylesheet" href="./static/css/main.css">
  </head>
  <body>

  <?php include TEMPLATE_PATH . '/header_nav' . TEMPLATE_EXTENSION; ?>

  <div class="bd-masthead" id="content">
    <div class="container-xl">
  