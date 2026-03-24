<?php
/**
  * Chat-Widget für die TaskManager-Anwendung
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: chat_widget.php 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Chat
  * @package TaskManager
  */

  // Nur anzeigen, wenn Benutzer eingeloggt ist
  if (isset($taskUser) && $taskUser->isLoggedIn())
  {
    $isPrivileged = (
      $taskUser->isSupervisor() ||
      $taskUser->isManager() ||
      $taskUser->isTeamLeader() ||
      $taskUser->isAdmin()
    );

    // Template einbinden
    include TEMPLATE_PATH . 'chat_widget' . TEMPLATE_EXTENSION;
  }