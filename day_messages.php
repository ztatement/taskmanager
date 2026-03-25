<?php
/**
  * Day Messages Page
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: day_messages.php 1 Dienstag, 10. Februar 2026, 06:34:09 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Day Messages
  * @package TaskManager
  * 
  * @description Displays and manages daily messages for calendar days
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  $taskUser->requireLogin();
  use classes\security\CsrfSecurity;
  use classes\services\DayMessageService;
  use classes\services\UploadService;

  $csrf = new CsrfSecurity();
  $uploadService = new UploadService($lang);
  $dayMessageService = new DayMessageService($taskDb, $taskUser, $csrf, $uploadService, $lang);

  // Handle POST requests (AJAX and standard)
  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    $result = $dayMessageService->handleRequest($_POST, $_FILES);

    // Auch auf explizite POST-Aktionen prüfen, um Fetch-API Calls ohne Header abzufangen
    $isApiAction = isset($_POST['save_message']) || isset($_POST['update_message']) || isset($_POST['delete_message']);

    if ($dayMessageService->isAjaxRequest() || $isApiAction)
    {
      header('Content-Type: application/json');
      echo json_encode($result);
      exit();
    }

    // Handle standard form submission redirects
    if ($result['success'] ?? false)
    {
      $action = $result['action'] ?? 'saved'; // e.g., 'saved', 'updated', 'deleted'
      header("Location: day_messages.php?success=" . $action);
      exit();
    } 
    else
    {
      header("Location: day_messages.php?error=1"); // Generic error
      exit();
    }
  }

  require_once './includes/header.php';

  // Monat und Jahr aus der URL holen, Standard ist der aktuelle Monat/Jahr
  $selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
  $selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
  $search = $_GET['search'] ?? '';

  $viewData = $dayMessageService->getViewData($selectedYear, $selectedMonth, $search);
  extract($viewData); // Extracts variables for the template

  include TEMPLATE_PATH . 'day_messages' . TEMPLATE_EXTENSION;

  $close_container=true;
  include './includes/footer.php';
