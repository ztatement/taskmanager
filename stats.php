<?php
/**
  * Statistikseite
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: stats.php 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Statistikseite
  * @package TaskManager
  * 
  * @description Zeigt Statistiken und Diagramme der Task-Auswertungen an
  */

  declare(strict_types=1);

  require_once './includes/init.php';

  $taskUser->requireLogin();

  use classes\services\StatsService;

  $statsService = new StatsService( $taskDb, $taskUser );

  // Monat/Jahr aus GET-Parametern oder aktuell
  $selectedMonth = isset( $_GET['month'] )?( int )$_GET['month']:( int )date( 'm' );
  $selectedYear = isset( $_GET['year'] )?( int )$_GET['year']:( int )date( 'Y' );

  // Daten vom Service holen
  $data = $statsService->getStatsData( $selectedMonth, $selectedYear );
  extract( $data );

  require_once './includes/header.php';

  include TEMPLATE_PATH . 'stats' . TEMPLATE_EXTENSION;

  $close_container = true;
  include './includes/footer.php';
