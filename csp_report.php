<?php

  declare(strict_types=1);

/**
  * Endpoint für Content Security Policy (CSP) Berichte.
  *
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: csp_report.php $
  * @created $Id: 1 Mittwoch, 8. April 2026, 10:35:45 GMT+0200Z ztatement $
  *
  * @description
  * Endpoint für Content Security Policy (CSP) Berichte.
  * Erhält Fehlermeldungen vom Browser, wenn Ressourcen blockiert werden.
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Minimale Initialisierung: Nur Config und Autoloader
  require_once './includes/config.php';
  require_once './includes/autoload.php';

  use classes\services\LogService;

  // Bericht aus dem Request-Body lesen
  $json = file_get_contents('php://input');

  try {
    $logger = new LogService(LOG_DIR);

    if ($json)
    {
      $data = json_decode($json, true);

      $report = null;

      // Unterstützung verschiedener Browser-Formate:
      // - { "csp-report": { ... } }
      // - { "report": { ... } } (report-to)
      // - direktes Report-Objekt (einige Agenten)
      if (is_array($data))
      {
        if (isset($data['csp-report']) && is_array($data['csp-report']))
        {
          $report = $data['csp-report'];
        }
        elseif (isset($data['report']) && is_array($data['report']))
        {
          $report = $data['report'];
        }
        elseif (isset($data['document-uri']) || isset($data['violated-directive']))
        {
          // Manche Clients senden das Report-Objekt direkt
          $report = $data;
        }
      }

      if (is_array($report))
      {
        // Nachricht für das Log formatieren
        $logMessage = "CSP Verstoß: Blockiert wurde '" . ($report['blocked-uri'] ?? $report['blocked_uri'] ?? 'unbekannt') .
                      "' durch Direktive '" . ($report['violated-directive'] ?? $report['violated_directive'] ?? 'N/A') .
                      "' auf Seite '" . ($report['document-uri'] ?? $report['document_uri'] ?? 'N/A') . "'.";

        // Zusätzliche Details (falls vorhanden)
        if (!empty($report['source-file']))
        {
          $logMessage .= " Quelle: " . $report['source-file'];
        }
        if (!empty($report['effective-directive']))
        {
          $logMessage .= " (effektiv: " . $report['effective-directive'] . ")";
        }

        $logger->log($logMessage, basename(LOG_FILE_CSP));
      }
      else
      {
        // Unbekanntes Format oder JSON-Fehler: logge Rohdaten + Header für Analyse
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $headerText = json_encode($headers);
        $rawMsg = "Unbekanntes CSP-Report-Format. Raw Body: " . substr($json, 0, 4000);
        $rawMsg .= " | Headers: " . $headerText;
        $logger->log($rawMsg, basename(LOG_FILE_CSP));
      }
    }
    else
    {
      // Kein Body empfangen: logge Request-Infos
      $infoMsg = "Leerer CSP-Report empfangen von " 
        . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') 
        . " auf " . ($_SERVER['REQUEST_URI'] ?? 'N/A');
      $logger->log($infoMsg, basename(LOG_FILE_CSP));
    }
  }
  catch (Throwable $e)
  {
    // Wenn Logging selbst fehlschlägt, fallback in das PHP-Error-Log
    error_log("CSP-Report-Handler Fehler: " . $e->getMessage());
  }

  // 204 No Content signalisiert dem Browser den Erfolg ohne unnötige Datenübertragung
  http_response_code(204);