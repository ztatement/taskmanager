<?php
/**
  * Passwort vergessen Seite
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: forgot_password.php 1 Montag, 9. Februar 2026, 09:57:52 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Passwort vergessen
  * @package TaskManager
  * 
  * @description Erlaubt Benutzern, ihr Passwort zurückzusetzen
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  use classes\services\ForgotPasswordService;
  use classes\security\CsrfSecurity;

  $error='';
  $success='';
  $resetLink='';

  // Aktuelle Sprache für das Flag-Icon im Template holen
  $currentLang = $_SESSION['current_lang_key'] ?? 'german_de-DE';

  $csrf=new CsrfSecurity();

  if( $_SERVER['REQUEST_METHOD']==='POST' )
  {
    $service=new ForgotPasswordService( $taskDb, $taskUser, $csrf, $lang );

    $result=$service->handleRequest( $_POST );

    if( isset( $result['error'] ) )
    {
      $error=$result['error'];
    }
    if( isset( $result['success'] ) )
    {
      $success=$result['success'];
    }
    if( isset( $result['reset_link'] ) )
    {
      $resetLink=$result['reset_link'];
    }
  }

  $title = '<title>' . ($lang['forgot_password_page_title'] ?? 'Passwort vergessen') . '</title>';

  include TEMPLATE_PATH . 'forgot_password' . TEMPLATE_EXTENSION;