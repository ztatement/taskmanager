<?php
/**
  * Profil Seite
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: profile.php 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Profile
  * @package TaskManager
  * 
  * @description Benutzerprofil verwalten
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  use classes\security\CsrfSecurity;
  use classes\services\ProfileService;

  $taskUser->requireLogin();
  $csrf = new CsrfSecurity();
  $profileService = new ProfileService( $taskDb, $taskUser, $csrf, $lang );

  $error = '';
  $success = '';

  // Handle all POST actions via ProfileService
  $result = $profileService->handleRequest( $_POST );
  $error = $result['error']??'';
  $success = $result['success']??'';

  // Datei-Download verarbeiten, falls angefordert
  if (isset($result['download_file']) && file_exists($result['download_file']))
  {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($result['download_file']));
    readfile($result['download_file']);
    exit;
  }

  // Header erst einbinden, nachdem alle Redirects/Logik abgearbeitet sind
  require_once './includes/header.php';

  // Profildaten laden
  $profileData = $profileService->getProfileData();
  extract($profileData); // Entpackt $user, $is2FAEnabled, $dbInfo, $availableLanguages

  include TEMPLATE_PATH . 'profile' . TEMPLATE_EXTENSION;

  include './includes/footer.php';
