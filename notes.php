<?php
/**
  * Notes Page
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: notes.php 1 Dienstag, 10. Februar 2026, 06:34:09 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Notes
  * @package TaskManager
  * 
  * @description Manages user notes
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  use classes\security\CsrfSecurity;
  use classes\services\ConfigService;
  use Throwable;

  // Nur eingeloggte User
  if (!$taskUser->isLoggedIn())
  {
    http_response_code(401);
    echo json_encode(['error' => 'Nicht eingeloggt']);
    exit;
  }

  $userId = $taskUser->getUserId();

  // GET: Notiz laden
  if ($_SERVER['REQUEST_METHOD'] === 'GET')
  {
    $note = $taskDb->users->getUserNotes($userId);
    header('Content-Type: application/json');
    echo json_encode(['note' => $note]);
    exit;
  }

  // POST: Notiz speichern
  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    header('Content-Type: application/json');

    try
    {
      $csrf = new CsrfSecurity();
      $token = $_POST['csrf_token'] ?? '';

      // CSRF Prüfung (Token muss vom JS gesendet werden)
      if (!$csrf->validateToken($token))
      {
        // Sende 200 mit error message, damit JS es als JSON parsen kann (und nicht in den catch block läuft)
        echo json_encode(['error' => 'Ungültiges Sicherheitstoken. Bitte Seite neu laden.']);
        exit;
      }

      $note = $_POST['note'] ?? '';
      
      // Maximale Länge prüfen (Standard: 10000)
      $config = new ConfigService();
      $maxLength = (int)$config->get('note_max_length', 10000);
      
      if (mb_strlen($note) > $maxLength)
      {
        $note = mb_substr($note, 0, $maxLength);
      }

      $success = $taskDb->users->updateUserNotes($userId, $note);
      echo json_encode(['success' => $success]);
    }
    catch (Throwable $e)
    {
      // Exception abfangen und als JSON zurückgeben
      if (isset($taskDb) && isset($taskDb->logs))
      {
        $taskDb->logs->log("Notes Save Error: " . $e->getMessage(), 'error.log');
      }
      echo json_encode(['success' => false, 'message' => 'Speicherfehler: ' . $e->getMessage()]);
    }
    exit;
  }
