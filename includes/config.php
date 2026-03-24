<?php
/**
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: config.php 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Konfiguration
  * @package TaskManager
  * 
  * @description Globale Konfiguration für TaskManager
  */

  // Globale Konfiguration
  define( 'CHARSET', 'UTF-8' );
  define( 'BASE_PATH', dirname( __DIR__ ) . '/' );
  define( 'LANGUAGE_PATH', BASE_PATH . 'static/languages/' );

  // Session-Laufzeit in Sekunden
  // 1800 = 30 Minuten, 28800 = 8 Stunden
  define( 'SESSION_TIMEOUT_DURATION', 28800 );

  // Laufzeit für "Angemeldet bleiben" in Sekunden (30 Tage)
  define( 'SESSION_REMEMBER_DURATION', 2592000 );
 
  // Pfad zur SQLite-Datenbankdatei
  define( 'DB_FILE_PATH', __DIR__ . 'db/' );
  define( 'DB_FILE', DB_FILE_PATH . 'tasks.sqlite' );
  define( 'DB_FILE_USER_PATH', DB_FILE_PATH. 'users/' );

  // Pfad zu den Backups
  define( 'BACKUP_DIR', DB_FILE_PATH . 'backups/' );
  define( 'BACKUP_DIR_USERS', BACKUP_DIR . 'users/' );
   
  // Template Pfad
  define( 'TEMPLATE_PATH', './includes/templates/' );
  // Admin Templates
  define( 'ADMIN_TEMPLATES', './includes/templates/admin/' );
  // Endung für Templates
  define( 'TEMPLATE_EXTENSION', '.template.php' );

  // SweetAlert2 Design (Dark Mode)
  define( 'SWAL_BACKGROUND', '#212529' );
  define( 'SWAL_COLOR', '#f8f9fa' );
  define( 'SWAL_CONFIRM_BUTTON_COLOR', '#0d6efd' );
  define( 'SWAL_CANCEL_BUTTON_COLOR', '#6c757d' );

  // Debug Modus (Standardwert, falls Datenbank-Einstellung fehlt)
  define( 'DEBUG_MODE_DEFAULT', false );
  define( 'DEBUG_MODE', DEBUG_MODE_DEFAULT );

  // Loglevel
  define( 'LOG_LEVEL', 'WARNING' );

  // Logfile
  define( 'LOG_DIR', './.logs/' );
  define( 'LOG_PATH', LOG_DIR );
  define( 'LOG_FILE', LOG_DIR . 'debug.log' );
  define( 'LOG_FILE_ERROR', LOG_DIR . 'errors.log' );
  define( 'LOG_FILE_MISSING_TRANSLATIONS', LOG_DIR . 'missing_translations.log' );
  define( 'LOG_FILE_TASK_EDIT', LOG_DIR . 'task_edit.log' );
  define( 'LOG_FILE_BACKUP', LOG_DIR . 'backup.log' );

  // Notizen
  define( 'NOTE_MAX_LENGTH', 10000 );
