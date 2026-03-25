<?php
/**
  * Reset Password Page
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: reset_password.php 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Reset Password
  * @package TaskManager
  * 
  * @description Passwort zurücksetzen
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  use classes\services\ResetPasswordService;
  use classes\security\CsrfSecurity;

  $error = '';
  $success = '';
  $token = $_GET['token']??'';
  $showForm = true;

  // Aktuelle Sprache für das Flag-Icon im Template holen
  $currentLang = $_SESSION['current_lang_key'] ?? 'german_de-DE';

  $csrf = new CsrfSecurity();

  if( empty( $token ) )
  {
    $error = 'Ungültiger oder fehlender Token.';
    $showForm = false;
  }

  if( $_SERVER['REQUEST_METHOD'] === 'POST' && $showForm )
  {
    $service = new ResetPasswordService( $taskDb, $taskUser, $csrf, $lang );

    $result = $service->handleRequest( $_POST, $token );

    if( isset( $result['error'] ) )
    {
      $error = $result['error'];
    }
    if( isset( $result['success'] ) )
    {
      $success = $result['success'];
    }
    if( isset( $result['hide_form'] ) && $result['hide_form'] )
    {
      $showForm = false;
    }
  }

  $title = '<title>' . ($lang['reset_password_page_title'] ?? 'Neues Passwort festlegen') . '</title>';

  include TEMPLATE_PATH . 'reset_password' . TEMPLATE_EXTENSION;
