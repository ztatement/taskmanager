<?php
/**
  * Autoload-Datei für die TaskManager-Anwendung
  * 
  * Diese Datei wird am Anfang jeder Seite geladen und initialisiert:
  * - Session-Sicherheit
  * - Datenbank-Verbindung
  * - System-Health-Checks
  * - Sprach-System
  * - Zentrale Klassen-Instanzen
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: autoload.php 1 Donnerstag, 12. Februar 2026, 15:15:03 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  *
  * @category Initialization
  * @package TaskManager
  */

  declare(strict_types=1);

  // Konfiguration laden (wird von vielen Klassen benötigt)
  require_once __DIR__ . '/config.php';
  require_once BASE_PATH . '/classes/Autoloader.php';
