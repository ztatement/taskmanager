<?php
/**
  * 
  * TaskManager - ein einfaches und leichtes PHP Aufgaben-Management System auf Basis von PHP und SQLite
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: task.php 1 Donnerstag, 12. Februar 2026, 20:49:51 GMT+0200Z ztatement $
  *
  * @link https://github.com/ztatement/taskmanager
  *
  * @license MIT
  *
  * @category Hauptseite
  * @package TaskManager
  *
  * @description Hauptseite für TaskManager
  */

  declare(strict_types=1);

/**
  * TaskManager - Aufgabenerfassung
  */
  require_once './includes/init.php';

  use classes\core\DatabaseConnection;
  use classes\core\TaskDatabase;
  use classes\security\CsrfSecurity;
  use classes\services\TaskService;
  use classes\services\BulkTaskService;
  use classes\user\TaskUser;

  $taskUser->requireLogin();

  $csrf=new CsrfSecurity();

  // --- AJAX HANDLER (Muss vor jeglichem HTML-Output stehen) ---
  $isAjaxRequest=
    isset( $_POST['save_task'] )||
    isset( $_POST['delete_task'] )||
    isset( $_GET['get_tasks'] )||
    isset( $_GET['get_task_details'] )||
    isset( $_GET['get_today_count'] )||
    isset( $_GET['get_assigned_tasks'] )||
    isset($_POST['bulk_create'])||
    isset( $_GET['get_templates'] );

  if( $isAjaxRequest )
  {
    // Fehleranzeige deaktivieren, damit HTML-Warnungen nicht das JSON zerstören
    ini_set( 'display_errors', 0 );
    error_reporting( E_ALL );

    try
    {

      $taskService=new TaskService( $taskDb, $taskUser, $csrf, $lang );

      // Bulk Create Handler
      if (isset($_POST['bulk_create']))
      {
        if (!$csrf->validateToken($_POST['csrf_token'] ?? ''))
        {
          echo json_encode([
            'success' => false,
            'message' => 'Ungültiger CSRF-Token. Bitte laden Sie die Seite neu.'
          ]);
          exit();
        }

        require_once './classes/services/BulkTaskService.php';
        $bulkService = new BulkTaskService($taskDb, $taskDb->tasks);
        
        $dateStr = $_POST['nacherfassung_datum'] ?? date('Y-m-d H:i:s');
        // Datum aus datetime-local (Y-m-d\TH:i) extrahieren, falls nötig
        if (strpos($dateStr, 'T') !== false)
        {
          $dateStr = str_replace('T', ' ', $dateStr);
        }
        $defaultTimestamp = strtotime($dateStr);

        $anlagen = [];

        // 1. CSV Upload prüfen
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK)
        {
          $tmpName = $_FILES['csv_file']['tmp_name'];
          if (($handle = fopen($tmpName, "r")) !== FALSE)
          {
            // BOM entfernen falls vorhanden (für Excel-Kompatibilität)
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") rewind($handle);
            
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
              // Leere Zeilen oder Header überspringen
              if (empty($data) || (count($data) === 1 && trim($data[0]) === '')) continue;
              if ($data[0] === 'Datum' && $data[1] === 'Auftrag') continue;

              // Prüfen ob es das Export-Format ist (mind. 8 Spalten)
              // Format: Datum;Auftrag;Vertragskonto;Anlage;Novomind-ID;Bemerkung;ReKo;Ergebnis
              if (count($data) >= 8)
              {
                $resMap = [
                  'erledigt' => '1',
                  'weitergeleitet' => '2',
                  'zurückgelegt' => '3',
                  'Wiedervorlage' => '4'
                ];
                $ergebnis = $resMap[$data[7]] ?? '1';

                $reko = trim($data[6]);
                if ($reko !== '' && is_numeric($reko)) $reko = 'Rechnung-Korrektur ' . $reko;
                elseif ($reko === '') $reko = 'Keine Auswahl';

                $csvDateStr = trim($data[0]); // Datum aus der ersten Spalte

                $anlagen[] = [
                  'anlagennummer' => trim($data[3]),
                  'auftrag' => trim($data[1]),
                  'vertragskontonummer' => trim($data[2]),
                  'novomind_id' => trim($data[4]),
                  'bemerkung' => trim($data[5]),
                  'reko' => $reko,
                  'ergebnis' => $ergebnis,
                  'start_time_override' => strtotime($csvDateStr)
                ];
              }
              else {
                // Fallback: Einfache Liste, erste Spalte als Anlage
                $anlagen[] = trim($data[0]);
              }
            }
            fclose($handle);
          }
        }
        else {
          // 2. Textarea Fallback
          $list = $_POST['anlagen_liste'] ?? '';
          $lines = preg_split('/\r\n|\r|\n/', $list);
          foreach ($lines as $line)
          {
            if (trim($line) !== '')
            {
                $anlagen[] = [
                    'anlagennummer' => trim($line),
                    'start_time_override' => $defaultTimestamp,
                    'end_time_override' => $defaultTimestamp + 3 // 3 Sekunden Dauer für Listen-Erfassung
                ];
            }
          }
        }

        // Leere Einträge entfernen
        $anlagen = array_filter($anlagen, function($v)
        {
          if (is_array($v)) return !empty($v['anlagennummer']);
          return !empty(trim($v));
        });

        $baseData = $_POST; // Enthält auftrag, ergebnis, reko, bemerkung

        $result = $bulkService->createBulkTasks($taskUser->getUserId(), $defaultTimestamp, $anlagen, $baseData);

        ob_clean(); // Puffer leeren für sauberes JSON
        header('Content-Type: application/json');
        echo json_encode($result);

        exit();
      }

      $response=$taskService->handleRequest( $_POST, $_GET );

      if( $response )
      {
        ob_clean(); // Puffer leeren für sauberes JSON
        header( 'Content-Type: application/json' );
        if( isset( $response['http_code'] ) )
        {
          http_response_code( $response['http_code'] );
        }
        echo json_encode( $response['data'] );

        exit();
      }
    }
    catch( Throwable $e )
    {
      ob_clean(); // Puffer leeren für sauberes JSON
      header( 'Content-Type: application/json' );
      http_response_code( 500 );
      echo json_encode( [
        'success'=>false,
        'message'=>'Server Error: '.$e->getMessage()
      ] );

      exit();
    }
  }

  // --- ENDE AJAX HANDLER ---


  $taskService=new TaskService( $taskDb, $taskUser, $csrf, $lang );

  // Daten für die Anzeige laden
  $viewData=$taskService->getIndexViewData();

  $assignableUsers=$viewData['assignableUsers'];
  $templates=$viewData['templates'];
  $todayCount=$viewData['todayCount'];
  $btnClass=$viewData['btnClass'];
  $badgeClass=$viewData['badgeClass'];
  $announcement = $viewData['announcement'];
  $announcement2 = $viewData['announcement2'];

  require_once './includes/header.php'; // Lädt den neuen, eigenständigen Header
  $base_path='';

  include TEMPLATE_PATH . 'task' . TEMPLATE_EXTENSION;

  $close_container=true;
  include './includes/footer.php';
