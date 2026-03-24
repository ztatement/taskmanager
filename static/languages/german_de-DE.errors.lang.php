<?php
/**
  * Fehler-Sprachdatei der deutsch übersetzten Sprach-Variablen
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24 $Id:
  * @file static/languages/german_de-DE.errors.lang.php 1 Sun Feb 15 2026 16:31:28 GMT+0100Z ztatement $
  *
  * @description Fehler-Sprachdatei für die deutsche Übersetzung
  * @lastModified 2026-02-15 16:31:28
  * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  $lang['error'] =                          "Fehler";

  // Login Errors
  $lang['login_error_timeout'] =            "Sie wurden aufgrund von Inaktivität automatisch ausgeloggt.";
  $lang['login_error_forced_logout'] =      "Ihre Berechtigungen oder Kontodaten wurden geändert. Bitte melden Sie sich erneut an.";
  $lang['register_error_disabled'] =        "Die Registrierung ist zurzeit deaktiviert.";
  $lang['register_error_domain_not_allowed'] = "Diese E-Mail-Domain ist nicht für die Registrierung zugelassen.";

  // Reset Password Errors
  $lang['reset_password_error_csrf'] =      "Ungültiger CSRF-Token. Bitte laden Sie die Seite neu.";
  $lang['reset_password_error_mismatch'] =  "Die Passwörter stimmen nicht überein oder sind leer.";
  $lang['reset_password_error_invalid_token'] = "Der Link zum Zurücksetzen ist ungültig oder abgelaufen.";
  $lang['reset_password_error_username_in_password'] = "Das Passwort darf den Benutzernamen nicht enthalten.";
  $lang['reset_password_error_common_password'] = "Das Passwort ist zu einfach und steht auf der Sperrliste (z.B. \"Passwort123\").";
  $lang['reset_password_error_breached_password'] = "Dieses Passwort wurde in einem bekannten Datenleck gefunden. Bitte wählen Sie ein sichereres Passwort.";
  $lang['reset_password_error_history'] =   "Sie können eines Ihrer letzten 3 Passwörter nicht wiederverwenden.";

  // Profile Errors
  $lang['profile_error_csrf'] =                   "Ungültiger Sicherheits-Token (CSRF). Bitte laden Sie die Seite neu.";
  $lang['profile_error_delete_account'] =         "Fehler beim Löschen des Kontos.";
  $lang['profile_error_2fa_invalid_code'] =       "Ungültiger Code. Einrichtung fehlgeschlagen.";
  $lang['profile_error_empty_fields'] =           "Benutzername und E-Mail sind erforderlich.";
  $lang['profile_error_invalid_email'] =          "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
  $lang['profile_error_username_exists'] =        "Dieser Benutzername ist bereits vergeben.";
  $lang['profile_error_email_exists'] =           "Diese E-Mail-Adresse wird bereits verwendet.";
  $lang['profile_error_password_mismatch'] =      "Die Passwörter stimmen nicht überein.";
  $lang['profile_error_password_too_short'] =     "Das Passwort muss mindestens 8 Zeichen lang sein.";
  $lang['profile_error_password_contains_username'] = "Das Passwort darf den Benutzernamen nicht enthalten.";
  $lang['profile_error_password_common'] =        "Das Passwort ist zu einfach und steht auf der Sperrliste (z.B. \"Passwort123\").";
  $lang['profile_error_password_in_history'] =    "Sie können eines Ihrer letzten 3 Passwörter nicht wiederverwenden.";
  $lang['profile_error_password_breached'] =      "Dieses Passwort wurde in einem bekannten Datenleck gefunden. Bitte wählen Sie ein sichereres Passwort.";
  $lang['profile_error_saving'] =                 "Fehler beim Speichern der Daten.";

  // Profile Confirmations (JS)
  $lang['profile_js_confirm_disable_2fa_title'] =    '2FA wirklich deaktivieren?';
  $lang['profile_js_confirm_disable_2fa_text'] =     'Ihr Konto ist dann weniger geschützt.';
  $lang['profile_js_confirm_disable_2fa_button'] =   'Ja, deaktivieren';
  $lang['profile_js_confirm_delete_account_title'] = 'Konto wirklich löschen?';
  $lang['profile_js_confirm_delete_account_text'] =  'Ihr Benutzerkonto wird dauerhaft gelöscht. Diese Aktion kann nicht rückgängig gemacht werden!';
  $lang['profile_js_confirm_delete_account_text_tasks'] = ' Auch alle Ihre erstellten Aufgaben werden unwiderruflich gelöscht.';
  $lang['profile_js_confirm_delete_account_button'] = 'Ja, endgültig löschen';
  $lang['profile_js_button_cancel'] =                'Abbrechen';

  // Task Errors
  $lang['task_error_manager_limit'] =       "Manager dürfen keine Aufgaben bearbeiten, die älter als der letzte Monat sind.";
  $lang['task_error_supervisor_limit'] =    "Supervisors dürfen keine Aufgaben bearbeiten, die älter als gestern sind.";
  $lang['task_error_team_leader_limit'] =   "Teamleiter dürfen keine Aufgaben bearbeiten, die älter als letzte Woche sind.";
  $lang['task_error_edit_too_old'] =        "Bearbeitung nicht möglich: Die Aufgabe ist älter als Gestern.";
  $lang['task_error_future_date'] =         "Das Datum der Nacherfassung darf nicht in der Zukunft liegen.";
  $lang['task_error_update_failed'] =       "Fehler beim Aktualisieren.";
  $lang['task_error_db_error'] =            "Datenbankfehler.";
  $lang['task_error_manager_delete_limit'] = "Manager dürfen keine Aufgaben löschen, die älter als der letzte Monat sind.";
  $lang['task_error_team_leader_delete_limit'] = "Teamleiter dürfen keine Aufgaben löschen, die älter als letzte Woche sind.";
  $lang['task_error_delete_too_old'] =      "Löschen nicht möglich: Die Aufgabe ist älter als Gestern.";
  $lang['task_error_delete_failed'] =       "Fehler beim Löschen.";
  $lang['task_error_not_found'] =           "Aufgabe nicht gefunden.";

  // Template Errors
  $lang['template_error_csrf'] =            "Ungültiger CSRF-Token. Bitte laden Sie die Seite neu.";
  $lang['template_error_title_missing'] =   "Bitte geben Sie einen Titel ein.";
  $lang['template_error_save'] =            "Fehler beim Speichern.";
  $lang['template_error_delete'] =          "Fehler beim Löschen.";

  // Day Message Errors
  $lang['day_msg_error_invalid_type'] =     "Ungültiger Dateityp. Erlaubt: %s";
  $lang['day_msg_error_too_large'] =        "Datei ist zu groß (max. %s).";
  $lang['day_msg_error_upload_move'] =      "Fehler beim Verschieben der Datei.";

  // Admin Errors
  $lang['admin_error_csrf'] =               "Ungültiger CSRF-Token. Bitte laden Sie die Seite neu.";
  $lang['admin_error_missing_fields'] =     "Alle Felder sind erforderlich.";
  $lang['admin_error_username_taken'] =     "Benutzername bereits vergeben.";
  $lang['admin_error_key_spaces'] =         "Der neue Schlüssel darf keine Leerzeichen enthalten.";
  $lang['admin_error_save_new'] =           "Fehler beim Speichern des neuen Eintrags.";
  $lang['admin_error_no_key'] =             "Kein Schlüssel zum Löschen angegeben.";
  $lang['admin_error_readonly'] =           "Die Einstellung '%s' ist schreibgeschützt und kann nicht gelöscht werden.";
  $lang['admin_error_setting_delete'] =     "Fehler beim Löschen der Einstellung '%s'.";
  $lang['admin_error_user_update'] =        "Fehler beim Aktualisieren des Benutzers.";
