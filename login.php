<?php

  declare(strict_types = 1);

/**
  * 
  * TaskManager - ein einfaches und leichtes PHP Aufgaben-Management System auf Basis von PHP und SQLite
  *
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  *
  * @version 1.1.0.2026.04.16
  * @file $Id: login.php $
  * @created $Id: 1 Montag, 9. Februar 2026, 09:57:52 GMT+0200Z ztatement $
  *
  * @description Login-Seite für TaskManager
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  require_once './includes/init.php';

  use classes\services\LoginService;
  use classes\security\CsrfSecurity;

  $error = '';
  $success = '';

  // Wenn bereits eingeloggt, direkt zur Startseite
  if( $taskUser->isLoggedIn() )
  {
    header( "Location: index.php" );
    exit();
  }

  // Aktuelle Sprache für das Flag-Icon im Template holen
  $currentLang = $_SESSION['current_lang_key']??'german_de-DE';

  $csrf = new CsrfSecurity();
  $loginService = new LoginService( $taskDb, $taskUser, $csrf, $lang );

  if( $_SERVER['REQUEST_METHOD'] === 'POST' )
  {
    $result = $loginService->handleLoginRequest( $_POST );

    if( isset( $result['redirect'] ) )
    {
      header( "Location: " . $result['redirect'] );
      exit();
    }
    elseif( isset( $result['error'] ) )
    {
      $error = $result['error'];
    }
  }

  if( isset( $_GET['success'] ) && $_GET['success'] == '1' )
  {
    $success = $lang['login_success_registration'];
  }

  if( isset( $_GET['timeout'] ) && $_GET['timeout'] == '1' )
  {
    $error = $lang['login_error_timeout'];
  }

  if( isset( $_GET['logout'] ) && $_GET['logout'] == '1' )
  {
    $success = $lang['login_success_logout'];
  }

  if( isset( $_GET['logout'] ) && $_GET['logout'] == 'forced' )
  {
    $error = $lang['login_error_forced_logout'];
  }

  $title = '<title>' . ( $lang['login_page_title']??'Login' ) . '</title>';

  include TEMPLATE_PATH . 'login' . TEMPLATE_EXTENSION;
