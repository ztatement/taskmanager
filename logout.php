<?php
/**
  *
  * TaskManager - ein einfaches und leichtes PHP Aufgaben-Management System auf Basis von PHP und SQLite
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: logout.php 1 Montag, 9. Februar 2026, 09:57:52 GMT+0200Z ztatement $
  *
  * @link https://github.com/ztatement/taskmanager
  *
  * @license MIT
  *
  * @category Hauptseite
  * @package TaskManager
  *
  * @description Logout-Seite für TaskManager
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  // Logout durchführen (Session zerstören)
  $taskUser->logout();

  // Fallback Redirect, falls logout() keinen Redirect macht
  header("Location: login.php?logout=1");
  exit();