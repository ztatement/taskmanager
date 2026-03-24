<?php
/**
  * Task Listen Seite
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: tasks_list.php 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Hauptseite
  * @package TaskManager
  * 
  * @description Zeigt eine Liste aller Tasks mit Filter- und Sortieroptionen an
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  use classes\services\ReportService;
  use classes\helpers\PaginationHelper;
  use classes\helpers\Helpers;
  use classes\security\CsrfSecurity;

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

  $userId =               $taskUser->getUserId();

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

    // Supervisor/TeamLeader sehen keine Admins/Manager in der Liste
    if ($taskUser->isSupervisor() || $taskUser->isTeamLeader())
    {
      $allUsers = array_filter($allUsers, function ($u) {
        return !in_array($u['role'], ['admin', 'manager']);
      });
    }

    if (!empty($filterUser))
    {
      $requestedUserId = (int)$filterUser;
      if ($taskUser->isSupervisor() || $taskUser->isTeamLeader())
      {
        $targetUser = $taskDb->users->getTaskUserById($requestedUserId);
        if ($targetUser && !in_array($targetUser['role'], ['admin', 'manager']))
        {
          $userId = $requestedUserId;
        }
      }
      else
      {
        $userId = $requestedUserId;
      }
    }
  }

  // --- 4. EXPORT LOGIK ---
  if (isset($_GET['export']) && $_GET['export'] === 'csv')
  {
    // Alle gefilterten Aufgaben ohne Seitenlimit abrufen
    $tasksToExport = $taskDb->tasks->getAllTasksFiltered(
      $userId, $search, $filterErgebnis, $filterReko, $sort, $order, 
      $filterPeriod, $customStart, $customEnd, $highlightedIds
    );

    $reportService = new ReportService($lang);
    $reportService->exportTasksAsCsv($tasksToExport);
    exit();
  }

  // --- 5. ANZEIGE LOGIK ---
  require_once './includes/header.php';

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
    $userId, $limit, $offset, $search, $filterErgebnis, $filterReko, $sort, $order, 
    $filterPeriod, $customStart, $customEnd, $highlightedIds
  );
  $totalTasks = $taskDb->tasks->countAllTasks(
    $userId, $search, $filterErgebnis, $filterReko, $filterPeriod, $customStart, $customEnd, $highlightedIds
  );
  $totalPages = PaginationHelper::getTotalPages($totalTasks, $limit);

  // Ergebnis-Mapping
  $resultMap = [
    '1' => 'erledigt', 
    '2' => 'weitergeleitet', 
    '3' => 'zurückgelegt', 
    '4' => 'Wiedervorlage'
  ];

  // ReKo Optionen
  $rekoOptions = [
    'Rechnung-Korrektur 1', 'Rechnung-Korrektur 2', 'Rechnung-Korrektur 3', 
    'Rechnung-Korrektur 4', 'Rechnung-Korrektur 5', 'Rechnung-Korrektur 6', 
    'Rechnung-Korrektur 7', 'Rechnung-Korrektur 8', 'Rechnung-Korrektur 9', 
    'Rechnung-Korrektur >9'
  ];

  // Helper-Funktion für Dauer-Formatierung
  function formatDuration($seconds)
  {
    $m = floor($seconds / 60);
    $s = $seconds % 60;
    return ($m > 0 ? $m . 'm ' : '') . $s . 's';
  }

  $sortLinkHelper = function($column, $label) use ($sort, $order, $search, $filterErgebnis, $filterReko, $filterPeriod, $customStart, $customEnd, $filterUser)
  {
    $params = [
      'search' => $search,
      'ergebnis' => $filterErgebnis,
      'reko' => $filterReko,
      'period' => $filterPeriod,
      'start' => $customStart,
      'end' => $customEnd,
      'user_filter' => $filterUser
    ];
    return Helpers::getSortLink($column, $label, $sort, $order, $params);
  };

  include TEMPLATE_PATH . 'tasks_list' . TEMPLATE_EXTENSION;

  $close_container=true;
  include './includes/footer.php';
