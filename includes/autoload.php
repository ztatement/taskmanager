<?php

  declare(strict_types=1);

/**
  * Autoload-Datei für die TaskManager-Anwendung
  *
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: autoload.php $
  * @created $Id: 1 Donnerstag, 12. Februar 2026, 15:15:03 GMT+0200Z ztatement $
  * 
  * @description Autoload-Datei für die TaskManager-Anwendung
  * Diese Datei wird am Anfang jeder Seite geladen und initialisiert:
  * - Session-Sicherheit
  * - Datenbank-Verbindung
  * - System-Health-Checks
  * - Sprach-System
  * - Zentrale Klassen-Instanzen
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Konfiguration laden (wird von vielen Klassen benötigt)
  require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
  require_once BASE_PATH . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Autoloader.php';
