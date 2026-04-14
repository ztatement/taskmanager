<?php
/**
  * 
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: config.php $
  * @created $Id: 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @description Globale Konfiguration für den TaskManager
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Globale Konfiguration
  define( 'CHARSET', 'UTF-8' );
  define( 'BASE_PATH', dirname( __DIR__ ) . "/" ); 
  define( 'INCLUDES_PATH', BASE_PATH . 'includes' . DIRECTORY_SEPARATOR );
  define( 'STATIC_PATH', BASE_PATH . 'static' . DIRECTORY_SEPARATOR );
  define( 'LANGUAGE_PATH', STATIC_PATH . 'languages' . DIRECTORY_SEPARATOR );

  // Dateisystem-Pfade (für PHP internal, z.B. filemtime)
  define( 'CSS_PATH', STATIC_PATH . 'css' . DIRECTORY_SEPARATOR );
  define( 'JS_PATH', STATIC_PATH . 'js' . DIRECTORY_SEPARATOR );

  // Web-URLs (für Browser/HTML)
  define( 'CSS_URL', 'static/css/' );
  define( 'JS_URL', 'static/js/' );

  // Session-Laufzeit in Sekunden
  // 1800 = 30 Minuten, 28800 = 8 Stunden
  define( 'SESSION_TIMEOUT_DURATION', 28800 );

  // Laufzeit für "Angemeldet bleiben" in Sekunden (30 Tage)
  define( 'SESSION_REMEMBER_DURATION', 2592000 );

  // Eindeutiger Session-Name (Präfix) zur Vermeidung von Konflikten auf derselben Domain
  define( 'SESSION_PREFIX', 'tm_' );

  // Pfad zur SQLite-Datenbankdatei
  define( 'DB_FILE_PATH', BASE_PATH . 'includes' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR );
  define( 'DB_FILE', DB_FILE_PATH . 'tasks.sqlite' );
  define( 'DB_FILE_USER_PATH', DB_FILE_PATH . 'users' . DIRECTORY_SEPARATOR );
  define( 'DB_FILE_SMS', DB_FILE_PATH . 'sms.sqlite' );

  // Pfad zu den Backups
  define( 'BACKUP_DIR', DB_FILE_PATH . 'backups' . DIRECTORY_SEPARATOR );
  define( 'BACKUP_DIR_USERS', BACKUP_DIR . 'users' . DIRECTORY_SEPARATOR );
  
  // Template Pfad
  define( 'TEMPLATE_PATH', BASE_PATH . 'includes' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR );
  // Admin Templates
  define( 'ADMIN_TEMPLATES', TEMPLATE_PATH . 'admin' . DIRECTORY_SEPARATOR );
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
  define( 'LOG_LEVEL', 'DEBUG' );

  // Logfile
  define( 'LOG_DIR', BASE_PATH . '.logs' . DIRECTORY_SEPARATOR );
  define( 'LOG_PATH', LOG_DIR );
  define( 'LOG_FILE', LOG_DIR . 'debug.log' );
  define( 'LOG_FILE_ERROR', LOG_DIR . 'errors.log' );
  define( 'LOG_FILE_MISSING_TRANSLATIONS', LOG_DIR . 'missing_translations.log' );
  define( 'LOG_FILE_TASK_EDIT', LOG_DIR . 'task_edit.log' );
  define( 'LOG_FILE_BACKUP', LOG_DIR . 'backup.log' );
  define( 'LOG_FILE_CSP', LOG_DIR . 'csp_violations.log' );

  // Notizen
  define( 'NOTE_MAX_LENGTH', 10000 );

  // Externe Flaggen-URL ohne Datei
  define('FLAG_CSS', 'https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css');
  define('FLAGS_URL', 'https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/flags/4x3/');

  // Bootstrap
  define('BOOTSTRAP_CSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
  define('BOOTSTRAP_JS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js');

  // SweetAlert
  define('SWEETALERT_CSS', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
  define('SWEETALERT_JS', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js');

  // Fonts
  define('FONT_AWESOME_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
  define('FONT_AWESOME_JS', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js');
