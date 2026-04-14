<?php

  declare(strict_types=1);

/**
  * Task Listen Seite
  * 
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.27
  * @file $Id: tasks_list.php $
  * @created $Id: Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  * 
  * @description TaskManager - Aufgabenliste
  * Zeigt eine Liste aller Tasks mit Filter- und Sortieroptionen an
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  require_once './includes/init.php';

  use classes\services\ReportService;
  use classes\helpers\PaginationHelper;
  use classes\helpers\Helpers;
  use classes\security\CsrfSecurity;
  use classes\services\UserVisibilityService;

  $taskUser->requireLogin();

  $csrf = new CsrfSecurity();

  // --- 1. PARAMETER INITIALISIEREN ---
  $search =               $_GET['search'] ?? '';
  $filterErgebnis =       $_GET['ergebnis'] ?? '';
  $filterReko =           $_GET['reko'] ?? '';
  $sort =                 $_GET['sort'] ?? 'start_time';
  $order =                $_GET['order'] ?? 'DESC';
  $filterPeriod =         $_GET['period'] ?? 'today';
  $customStart =          $_GET['start'] ?? '';
  $customEnd =            $_GET['end'] ?? '';
  $filterUser =           $_GET['user_filter'] ?? '';
  $highlightedIdsParam =  $_GET['highlighted_ids'] ?? '';
  $highlightedIds =       !empty($highlightedIdsParam) ? explode(',', $highlightedIdsParam) : [];

  // Initialisierung der Abfrage-ID (Verwendung von effectiveUserId zur Vermeidung von Header-Kollisionen)
  $effectiveUserId = (int)$taskUser->getUserId();

  // --- 2. ROLLEN-EINSCHRÄNKUNGEN (DATUM) ---
  // Definiert Zeitlimits basierend auf der Rolle und wendet sie zentral an
  $limitDate = null; 
  if ($taskUser->isManager())
  {
    $limitDate = date('Y-m-d', strtotime('first day of last month'));
  }
  elseif ($taskUser->isTeamLeader())
  {
    $limitDate = date('Y-m-d', strtotime('monday last week'));
  }
  elseif ($taskUser->isSupervisor())
  {
    $limitDate = date('Y-m-d', strtotime('yesterday'));
  }

  if ($limitDate)
  {    
    // Wenn kein Startdatum gesetzt ist oder es zu weit zurückliegt, Limit erzwingen
    if (empty($customStart) || $customStart < $limitDate)
    {
      $customStart = $limitDate;
      $filterPeriod = 'today';
    }
  }

  // --- 3. BENUTZER-FILTER ---
  $allUsers = [];
  $showUserColumn = (
    $taskUser->isAdmin() || 
    $taskUser->isManager() || 
    $taskUser->isTeamLeader() || 
    $taskUser->isSupervisor()
  );

  if ($showUserColumn)
  {
    $allUsers = $taskDb->users->getAllTaskUsers();
    // Zentrale Sichtbarkeitsregeln anwenden
    $allUsers = UserVisibilityService::filterUsers($allUsers, $taskDb, $taskUser);

    // 1. Berechtigungsprüfung für den ausgewählten Benutzer-Filter
    if (!empty($filterUser))
    {
      $requestedUserId = (int)$filterUser;
      $targetUser = $taskDb->users->getTaskUserById($requestedUserId);
      if ($targetUser && UserVisibilityService::canViewUser($targetUser, $taskUser, $taskDb))
      {
        $effectiveUserId = $requestedUserId;
      }
    }
  }

  // --- 4. EXPORT LOGIK ---
  if (isset($_GET['export']) && ($_GET['export'] === 'csv' || $_GET['export'] === 'pdf')) 
  {
    // Alle gefilterten Aufgaben ohne Seitenlimit abrufen
    $tasksToExport = $taskDb->tasks->getAllTasksFiltered(
      $effectiveUserId,
      $search, 
      $filterErgebnis, 
      $filterReko, 
      $sort, 
      $order, 
      $filterPeriod, 
      $customStart, 
      $customEnd, 
      $highlightedIds
    );

    $exportColsJson = $taskDb->settings->getSetting('export_task_columns')['value'] ?? '[]';
    $activeCols = json_decode($exportColsJson, true) ?: [];
    $logoSetting = $taskDb->settings->getSetting('report_pdf_logo')['value'] ?? '';

    $reportService = new ReportService($lang);
    if ($_GET['export'] === 'csv') 
    {
      $reportService->exportTasksAsCsv($tasksToExport, $activeCols);
    } 
    else 
    {
      $reportService->exportTasksAsPdf($tasksToExport, $activeCols, $logoSetting);
    }
    exit();
  }

  // --- 5. ANZEIGE LOGIK ---
  require_once INCLUDES_PATH . '/header.php';

  // --- DEBUG AUSGABE FÜR ADMINS ---
  $isDebugMode = ($taskDb->settings->getValue('debug_mode_enabled') === '1');
  if ($taskUser->isAdmin() && $isDebugMode)
  {
    echo '<div class="alert alert-warning mt-3 mx-3 shadow-sm border-warning">';
    echo '<strong><i class="fas fa-bug me-2"></i>Debug Info:</strong> ';
    echo 'Erkannte Rolle: <span class="badge bg-dark">' . htmlspecialchars($taskUser->getRole()) . '</span> | ';
    echo 'Deine ID: <code>' . $taskUser->getUserId() . '</code> | ';
    echo 'Gewählter Filter (GET): <code>' . htmlspecialchars($filterUser) . '</code> | ';
    echo 'Effektive Abfrage-ID: <span class="badge bg-danger">' . $effectiveUserId . '</span>';
    echo '</div>';
  }

  // Pagination Parameter
  $limit = 20;
  $page = PaginationHelper::getCurrentPage('page');
  $offset = PaginationHelper::getOffset($page, $limit);

  // Parameter für die URL-Generierung sammeln (ohne 'page')
  $urlParams = $_GET;
  unset($urlParams['page']);
  $baseQuery = http_build_query($urlParams);

  // Daten abrufen
  $tasks = $taskDb->tasks->getAllTasksPaginated(
    $effectiveUserId, 
    $limit, 
    $offset, 
    $search, 
    $filterErgebnis, 
    $filterReko, 
    $sort, 
    $order, 
    $filterPeriod, 
    $customStart, 
    $customEnd, 
    $highlightedIds
  );
  $totalTasks = $taskDb->tasks->countAllTasks(
    $effectiveUserId, 
    $search, 
    $filterErgebnis, 
    $filterReko, 
    $filterPeriod, 
    $customStart, 
    $customEnd, 
    $highlightedIds
  );
  $totalPages = PaginationHelper::getTotalPages($totalTasks, $limit);

  // Ergebnis-Mapping
  $resultMap = [
    '1' => $lang['result_done'] ?? 'erledigt', 
    '2' => $lang['result_forwarded'] ?? 'weitergeleitet', 
    '3' => $lang['result_on_hold'] ?? 'zurückgelegt', 
    '4' => $lang['result_resubmission'] ?? 'Wiedervorlage'
  ];

  // ReKo Optionen
  $rekoOptions = [
    'Rechnung-Korrektur 1', 'Rechnung-Korrektur 2', 'Rechnung-Korrektur 3', 
    'Rechnung-Korrektur 4', 'Rechnung-Korrektur 5', 'Rechnung-Korrektur 6', 
    'Rechnung-Korrektur 7', 'Rechnung-Korrektur 8', 'Rechnung-Korrektur 9', 
    'Rechnung-Korrektur >9'
  ];

  $sortLinkHelper = function($column, $label) 
                    use (
                      $sort, 
                      $order, 
                      $search, 
                      $filterErgebnis, 
                      $filterReko, 
                      $filterPeriod, 
                      $customStart, 
                      $customEnd, 
                      $filterUser, 
                      $highlightedIdsParam) 
  {
    $params = [
      'search' => $search,
      'ergebnis' => $filterErgebnis,
      'reko' => $filterReko,
      'period' => $filterPeriod,
      'start' => $customStart,
      'end' => $customEnd,
      'user_filter' => $filterUser,
      'highlighted_ids' => $highlightedIdsParam
    ];
    return Helpers::getSortLink(
      $column, 
      (string)($label ?? $column),
      $sort, 
      $order, 
      $params
    );
  };

  include TEMPLATE_PATH . 'tasks_list' . TEMPLATE_EXTENSION;

  $close_container=true;
  include './includes/footer.php';
