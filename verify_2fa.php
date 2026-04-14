<?php

  declare(strict_types=1);

/**
  * Zwei-Faktor-Authentifizierungsseite für TaskManager
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: verify_2fa.php $
  * @created $Id: 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  *
  * @description 2FA Verifizierungsseite für TaskManager
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Zentrale Initialisierung: Lädt Autoloader, Konfiguration, Datenbankverbindung und Sprachdateien
  require_once './includes/init.php';

  use classes\services\Verify2FAService;
  use classes\security\CsrfSecurity;

  $error='';

  // Wenn keine temporäre Session existiert, zurück zum Login
  if( !isset( $_SESSION['temp_2fa_user_id'] ) )
  {
    header( "Location: login.php" );
    exit();
  }

  $csrf = new CsrfSecurity();
  $service = new Verify2FAService($taskUser, $csrf, $lang);

  if( $_SERVER['REQUEST_METHOD']==='POST' )
  {
    $result = $service->handleRequest($_POST);

    if (isset($result['redirect']))
    {
      header("Location: " . $result['redirect']);
      exit();
    }
    elseif (isset($result['error']))
    {
      $error = $result['error'];
    }
  }

  $title = '<title>' . ($lang['2fa_page_title'] ?? '2FA Verifizierung') . '</title>';

  include TEMPLATE_PATH . 'verify_2fa' . TEMPLATE_EXTENSION;
