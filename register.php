<?php
/**
  *
  * TaskManager - ein einfaches und leichtes PHP Aufgaben-Management System auf Basis von PHP und SQLite
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: register.php 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  *
  * @link https://github.com/ztatement/taskmanager
  *
  * @license MIT
  *
  * @category Hauptseite
  * @package TaskManager
  *
  * @description Registrierungsseite für TaskManager
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  use classes\services\RegisterService;
  use classes\security\CsrfSecurity;
  use classes\system\Localization;

  $error = '';
  $success = '';

  $csrf=new CsrfSecurity();

  // Wenn bereits eingeloggt, direkt zur Startseite
  if( $taskUser->isLoggedIn() )
  {
    header( "Location: index.php" );
    exit();
  }
  // --- SPRACHE LADEN ---
  if (session_status() === PHP_SESSION_NONE)
  {
    session_start();
  }

  // 1. Handle explicit language change from user
  if (isset($_GET['lang']))
  {
      $availableLanguages = Localization::getAvailableLanguages();
      if (array_key_exists($_GET['lang'], $availableLanguages))
      {
        $_SESSION['public_lang'] = $_GET['lang'];
      }
      
      $queryParams = $_GET;
      unset($queryParams['lang']);
      
      $redirectUrl = 'register.php';
      if (!empty($queryParams))
      {
        $redirectUrl .= '?' . http_build_query($queryParams);
      }
      
      header('Location: ' . $redirectUrl);
      exit();
  }

  // Determine current language
  $currentLang = null;
  if (isset($_SESSION['public_lang']))
  {
    $currentLang = $_SESSION['public_lang'];
  }
  elseif ($detectedLang = Localization::detectBrowserLanguage())
  {
    $currentLang = $detectedLang;
  }
  else
  {
    $langSetting = $taskDb->settings->getSetting('default_page_language');
    $currentLang = (is_array($langSetting) && !empty($langSetting['value'])) ? $langSetting['value'] : 'german_de-DE';
  }

  $langFile = LANGUAGE_PATH . $currentLang . '.lang.php';
  if (file_exists($langFile))
  {
    require_once $langFile;
  }
  else
  {
    require_once LANGUAGE_PATH . 'german_de-DE.lang.php';
  }
  // --- ENDE SPRACHE LADEN ---

  $registerService = new RegisterService( $taskDb, $taskUser, $csrf, $lang );

  // Check if registration is enabled
  $registrationSetting = $taskDb->settings->getSetting('registration_enabled');
  $registrationEnabled = ($registrationSetting && $registrationSetting['value'] === '1');

  if (!$registrationEnabled)
  {
    $error = $lang['register_error_disabled'] ?? 'Die Registrierung ist zurzeit deaktiviert.';
  } 
  elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    $result = $registerService->handleRegistrationRequest($_POST);

    if (isset($result['redirect'])) {
      header("Location: " . $result['redirect']);
      exit();
    } 
    elseif (isset($result['error']))
    {
      $error = $result['error'];
    }
  }

  $title = '<title>' . ($lang['register_page_title'] ?? 'Registrierung') . '</title>';

  include TEMPLATE_PATH . 'register' . TEMPLATE_EXTENSION;
