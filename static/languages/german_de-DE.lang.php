<?php
/**
  * Allgemeine Deutsche Übersetzungen der Sprach-Variablen
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24 $Id: 
  * @file static/languages/german_de-DE.lang.php 1 2026-01-28 13:08:22Z ztatement $
  *
  * @description Hauptsprachdatei für die deutsche Übersetzung
  * @lastModified 2026-03-24 10:00:00
  * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  use classes\LanguageContainer;

  // Verwendung von LanguageContainer statt Array, um Warnungen bei fehlenden Keys zu vermeiden
  $lang = new LanguageContainer();

  // Lade die Fehler-Sprachdatei (mit Fallback auf Englisch ist in merge nicht nötig, wenn Datei existiert, aber für Vollständigkeit)
  $lang->merge(__DIR__ . '/german_de-DE.errors.lang.php', __DIR__ . '/english_en-US.errors.lang.php');

  // Lade die Admin-Sprachdatei nur wenn User Admin ist
  if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['task_user_role']) && $_SESSION['task_user_role'] === 'admin')
  {
    $lang->merge(__DIR__ . '/german_de-DE.admin.lang.php', __DIR__ . '/english_en-US.admin.lang.php');
  }

  // Allgemein
  $lang['language']=                        "de";
  //$lang['exception_title'] =                "Fehler";
  //$lang['edit'] =                           "bearbeiten";
  //$lang['delete'] =                         "löschen";
  //$lang['submit_button_ok'] =               "&nbsp;OK&nbsp;";
  $lang['app_name'] =                       "Task Manager";

    // Menü
  $lang['menu_home'] =                      "Startseite";
  $lang['menu_task_entry'] =                "Erfassung";
  $lang['menu_all_tasks'] =                 "Alle Aufgaben";
  $lang['menu_stats'] =                     "Statistik";
  $lang['menu_zst_calculator'] =            "ZST-Rechner";
  $lang['menu_templates'] =                 "Vorlagen";
  $lang['menu_day_messages'] =              "Tagesnotiz";
  $lang['menu_admin'] =                     "Admin";
  $lang['menu_manager'] =                   "Manager";
  $lang['menu_supervisor'] =                "Supervisor";
  $lang['menu_profile'] =                   "Mein Profil";
  $lang['menu_logout'] =                    "Logout";

  // register
  $lang['register_page_title'] =            "Task Registrierung";
  $lang['register_card_title'] =            "Task Manager Registrierung";
  $lang['register_label_username'] =        "Benutzername";
  $lang['register_label_password'] =        "Passwort";
  $lang['register_label_email'] =           "E-Mail";
  $lang['register_label_confirm_password'] = "Passwort bestätigen";
  $lang['register_button_register'] =       "Registrieren";
  $lang['register_link_login'] =            "Bereits ein Konto? Zum Login";

  // login
  $lang['login_page_title'] =               "Task Login";
  $lang['login_card_title'] =               "Task Manager Login";
  $lang['login_label_username'] =           "Benutzername";
  $lang['login_label_password'] =           "Passwort";
  $lang['login_label_remember_me'] =        "Angemeldet bleiben";
  $lang['login_button_login'] =             "Login";
  $lang['login_link_forgot_password'] =     "Passwort vergessen?";
  $lang['login_link_register'] =            "Noch kein Konto? Registrieren";
  $lang['login_success_logout'] =           "Sie wurden erfolgreich ausgeloggt.";
  $lang['login_success_registration'] =     "Registrierung erfolgreich! Sie können sich jetzt anmelden.";
  $lang['login_link_back'] =                "Zurück zum Login";

  // forgot_password
  $lang['forgot_password_page_title'] =     "Passwort vergessen";
  $lang['forgot_password_card_title'] =     "Passwort zurücksetzen";
  $lang['forgot_password_label_email'] =    "Ihre E-Mail-Adresse";
  $lang['forgot_password_button_submit'] =  "Link anfordern";
  $lang['forgot_password_link_login'] =     "Zurück zum Login";

  // reset_password
  $lang['reset_password_page_title'] =      "Neues Passwort festlegen";
  $lang['reset_password_card_title'] =      "Neues Passwort";
  $lang['reset_password_label_password'] =  "Neues Passwort";
  $lang['reset_password_label_confirm'] =   "Passwort bestätigen";
  $lang['reset_password_button_save'] =     "Passwort speichern";
  $lang['reset_password_link_login'] =      "Zurück zum Login";
  $lang['reset_password_success'] =         "Ihr Passwort wurde erfolgreich zurückgesetzt. Sie können sich jetzt anmelden.";

  // maintenance
  $lang['maintenance_title'] =              "Wartungsarbeiten";
  $lang['maintenance_header'] =             "Wartungsarbeiten";
  $lang['maintenance_admin_login'] =        "Admin Login";

  // profil
  $lang['profile_success_2fa_enabled'] =    "Zwei-Faktor-Authentifizierung erfolgreich aktiviert!";
  $lang['profile_success_2fa_disabled'] =   "Zwei-Faktor-Authentifizierung deaktiviert.";
  $lang['profile_success_updated'] =        "Profil erfolgreich aktualisiert.";

  // index
  $lang['index_welcome_back'] =             "Willkommen zurück,";
  $lang['index_title'] =                    "im TaskManager";
  $lang['index_subtitle'] =                 "Bitte wähle einen Bereich aus dem Menü.";
  $lang['index_button_to_task'] =           "Zur Erfassung";

  // task.php
  $lang['headline_tasks'] =                 "Vorgänge";
  $lang['dropdown_eam_processes'] =         "EAM Prozesse";
  $lang['dropdown_wwn_processes'] =         "WWN Prozesse";
  $lang['button_export'] =                  "Export";
  $lang['label_today'] =                    "Heute:";
  $lang['headline_process_unselected'] =    "Prozess - Bitte auswählen";
  $lang['label_assign_to'] =                "Zuweisen an";
  $lang['option_assign_to_me'] =            "Mir selbst";
  $lang['label_contract_account'] =         "Vertragskontonummer";
  $lang['label_malo_id'] =                  "MaLo-ID";
  $lang['label_facility_number'] =          "Anlagennummer";
  $lang['label_novomind_id'] =              "Novomind-ID";
  $lang['label_remark'] =                   "Bemerkung";
  $lang['label_result'] =                   "Ergebnis";
  $lang['option_please_select'] =           "Bitte wählen...";
  $lang['result_done'] =                    "erledigt";
  $lang['result_forwarded'] =               "weitergeleitet";
  $lang['result_on_hold'] =                 "zurückgelegt";
  $lang['result_resubmission'] =            "Wiedervorlage";
  $lang['headline_performance'] =           "Performance";
  $lang['unit_hours'] =                     "Std";
  $lang['unit_minutes'] =                   "Min";
  $lang['unit_seconds'] =                   "Sek";
  $lang['button_start'] =                   "Start";
  $lang['button_halt'] =                    "Halt";
  $lang['button_stop'] =                    "Stop";
  $lang['headline_actions'] =               "Aktionen";
  $lang['label_open_task'] =                "offener Vorgang";
  $lang['label_reko'] =                     "Rechnung-Korrektur";
  $lang['option_no_selection'] =            "Keine Auswahl";
  $lang['headline_backdating'] =            "Nacherfassung";
  $lang['option_auto_browser'] =            "Automatisch (Browser)";
  $lang['label_backdating_date'] =          "zu Datum erfassen";
  $lang['button_capture_list'] =            "Liste erfassen";
  $lang['headline_edit_mode'] =             "Bearbeitungsmodus";
  $lang['label_task_date'] =                "Aufgaben-Datum";
  $lang['label_confirm_changes'] =          "Änderungen bestätigen (Überschreiben)";
  $lang['button_new'] =                     "Neu";
  $lang['button_cancel'] =                  "Abbrechen";
  $lang['button_close'] =                   "Schließen";
  $lang['button_put_back'] =                "Zurücklegen";
  $lang['button_delete'] =                  "Löschen";
  $lang['button_note'] =                    "Notiz";
  $lang['button_template'] =                "Vorlage";
  $lang['headline_assigned_tasks'] =        "Offene / Zugewiesene Aufgaben";
  $lang['headline_on_hold_tasks_today'] =   "Zurückgelegte Aufgaben (Heute)";
  $lang['headline_last_3_tasks'] =          "Letzte 3 Aufgaben";
  $lang['th_datetime'] =                    "Datum/Zeit";
  $lang['th_process'] =                     "Auftrag";
  $lang['th_vk_short'] =                    "VK";
  $lang['th_duration'] =                    "Dauer";
  $lang['th_malo_id'] =                     "MaLo-ID";
  $lang['th_facility'] =                    "Anlage";
  $lang['th_result'] =                      "Ergebnis";
  $lang['th_action'] =                      "Aktion";
  $lang['swal_title_bulk_capture'] =        "Massenerfassung";
  $lang['swal_label_capture_date'] =        "Datum der Erfassung";
  $lang['swal_label_default_result'] =      "Ergebnis (Standard)";
  $lang['swal_label_csv_upload'] =          "CSV Datei hochladen";
  $lang['swal_help_csv_format'] =           "Format: Datum;Auftrag;Vertragskonto;Anlage;Novomind-ID;Bemerkung;ReKo;Ergebnis";
  $lang['swal_label_facility_list'] =       "Oder Liste von Anlagennummern (eine pro Zeile)";
  $lang['swal_label_default_reko'] =        "ReKo (Standard)";
  $lang['swal_label_default_remark'] =      "Bemerkung (Standard)";
  $lang['swal_button_capture'] =            "Erfassen";
  $lang['swal_validation_file_or_list'] =   "Bitte wählen Sie eine Datei aus oder geben Sie Anlagennummern ein.";
  $lang['swal_title_processing'] =          "Verarbeitung läuft";
  $lang['swal_text_wait'] =                 "Bitte warten, die Daten werden hochgeladen und verarbeitet...";
  $lang['swal_title_success'] =             "Erfolg";
  $lang['swal_title_error'] =               "Fehler";
  $lang['swal_text_error_occurred'] =       "Ein Fehler ist aufgetreten.";
  $lang['swal_title_export_tasks'] =        "Aufgaben exportieren";
  $lang['swal_text_select_date_range'] =    "Wählen Sie einen Datumsbereich für den Export.";
  $lang['swal_button_csv_export'] =         "CSV Export";
  $lang['swal_button_pdf_export'] =         "PDF Export";

  // tasks_list.php
  $lang['headline_all_tasks'] =             "Alle Aufgaben";
  $lang['label_filter_user'] =              "Benutzer filtern";
  $lang['option_my_account'] =              "Mein Account";
  $lang['label_filter_period'] =            "Zeitraum filtern";
  $lang['option_whole_year'] =              "Gesamtes Jahr";
  $lang['option_today'] =                   "Heute";
  $lang['option_yesterday'] =               "Gestern";
  $lang['option_this_week'] =               "Diese Woche";
  $lang['option_last_week'] =               "Letzte Woche";
  $lang['option_this_month'] =              "Dieser Monat";
  $lang['option_last_month'] =              "Letzter Monat";
  $lang['option_this_year'] =               "Dieses Jahr";
  $lang['option_last_year'] =               "Letztes Jahr";
  $lang['option_custom'] =                  "Benutzerdefiniert";
  $lang['label_start_date'] =               "Startdatum";
  $lang['label_end_date'] =                 "Enddatum";
  $lang['label_filter_reko'] =              "Nach ReKo filtern";
  $lang['option_all_reko'] =                "Alle ReKo";
  $lang['label_filter_result'] =            "Nach Ergebnis filtern";
  $lang['option_all_results'] =             "Alle Ergebnisse";
  $lang['placeholder_search'] =             "Suchen...";
  $lang['button_filter'] =                  "Filtern";
  $lang['button_reset'] =                   "Reset";
  $lang['title_sort_by_highlight'] =        "Nach Markierung sortieren";
  $lang['th_user'] =                        "Benutzer";
  $lang['th_date'] =                        "Startzeit";
  $lang['th_contract_account'] =            "Vertragskonto";
  $lang['text_no_tasks_found'] =            "Keine Aufgaben gefunden.";
  $lang['title_click_for_full_view'] =      "Klicken für vollständige Ansicht";
  $lang['title_click_to_copy'] =            "Klicken zum Kopieren";
  $lang['title_edit'] =                     "Bearbeiten";
  $lang['pagination_previous'] =            "Zurück";
  $lang['pagination_next'] =                "Weiter";
  $lang['swal_copied'] =                    "Kopiert";
  $lang['swal_details'] =                   "Details";
  $lang['swal_close'] =                     "Schließen";
  $lang['swal_title_welcome'] =             "Willkommen!";

  // stats.php
  $lang['headline_stats'] =                 "Statistik";
  $lang['label_period'] =                   "Zeitraum:";
  $lang['button_show'] =                    "Anzeigen";
  $lang['headline_details'] =               "Details";
  $lang['th_month'] =                       "Monat";
  $lang['th_count'] =                       "Anzahl";
  $lang['headline_distribution_by_result'] ="Verteilung nach Ergebnis (Gesamt)";
  $lang['headline_avg_duration'] =          "Ø Bearbeitungszeit (Minuten)";
  $lang['chart_label_task_count'] =         "Anzahl Aufgaben";
  $lang['chart_label_previous_month'] =     "Vormonat";
  $lang['chart_label_previous_year'] =      "Vorjahr";
  $lang['chart_label_avg_duration_min'] =   "Ø Dauer (Min)";
  $lang['chart_label_yearly_avg'] =         "Jahres-Ø";
  $lang['chart_unit_minutes'] =             "Minuten";

  // templates.php
  $lang['headline_templates_notes'] =       "Vorlagen & Notizen";
  $lang['button_new_template'] =            "Neue Vorlage";
  $lang['text_no_templates_found'] =        "Keine Vorlagen vorhanden.";
  $lang['label_updated'] =                  "Aktualisiert:";
  $lang['badge_hidden'] =                   "Versteckt";
  $lang['headline_edit_template'] =         "Vorlage bearbeiten";
  $lang['headline_create_template'] =       "Neue Vorlage erstellen";
  $lang['title_copy_content'] =             "Inhalt kopieren";
  $lang['label_title_subject'] =            "Titel / Betreff";
  $lang['label_content_steps'] =            "Inhalt / Arbeitsschritte";
  $lang['text_available_placeholders'] =    "Verfügbare Platzhalter (klicken zum Einfügen):";
  $lang['placeholder_date'] =               "Datum";
  $lang['placeholder_time'] =               "Uhrzeit";
  $lang['placeholder_year'] =               "Jahr";
  $lang['placeholder_user'] =               "Benutzername";
  $lang['placeholder_facility_nr'] =        "Anlagennummer";
  $lang['placeholder_reko'] =               "Rechnungskorrektur";
  $lang['label_show_in_quick_select'] =     "In der Schnellauswahl (Index) anzeigen";
  $lang['button_save'] =                    "Speichern";
  $lang['confirm_delete_template'] =        "Vorlage wirklich löschen?";

  // day_messages.php
  $lang['headline_day_messages_for'] =      "Tagesmitteilungen für";
  $lang['label_select_month'] =             "Monat wählen";
  $lang['label_select_year'] =              "Jahr wählen";
  $lang['title_apply_filter'] =             "Filter anwenden";
  $lang['title_reset_filter'] =             "Filter zurücksetzen & aktueller Monat";
  $lang['button_new_day_message'] =         "Neue Tagesnotiz";
  $lang['th_message'] =                     "Mitteilung";
  $lang['text_no_messages_found'] =         "Keine Mitteilungen vorhanden.";
  $lang['title_delete'] =                   "Löschen";

  // manager.php
  $lang['headline_manager_area'] =          "Manager Bereich";
  $lang['menu_announcements'] =             "Ankündigungen";
  $lang['menu_working_hours'] =             "Arbeitszeiten";
  $lang['menu_daily_goals'] =               "Tagesziele";
  $lang['menu_create_user'] =               "Benutzer erstellen";
  $lang['menu_user_list'] =                 "Benutzerliste";
  $lang['th_role'] =                        "Rolle";
  $lang['th_first_task'] =                  "Erste Aufgabe";
  $lang['th_last_task'] =                   "Letzte Aufgabe";
  $lang['th_timespan'] =                    "Zeitspanne";
  $lang['text_no_data_for_date'] =          "Keine Daten für dieses Datum gefunden.";
  $lang['text_open'] =                      "Offen";
  $lang['label_level1_blue'] =              "Stufe 1 (Blau)";
  $lang['label_level2_green'] =             "Stufe 2 (Grün)";
  $lang['label_level3_gold'] =              "Stufe 3 (Gold)";
  $lang['label_password'] =                 "Passwort";
  $lang['label_email'] =                    "E-Mail";
  $lang['button_create'] =                  "Erstellen";
  $lang['th_status'] =                      "Status";
  $lang['th_last_login'] =                  "Letzter Login";
  $lang['th_actions'] =                     "Aktionen";
  $lang['status_online'] =                  "Online";
  $lang['status_offline'] =                 "Offline";
  $lang['title_unlock'] =                   "Entsperren";
  $lang['confirm_really_delete'] =          "Wirklich löschen?";

  // supervisor.php
  $lang['headline_team_overview'] =         "Team Übersicht";
  $lang['headline_team_performance_today'] ="Team-Leistung heute (Aufgaben pro Stunde)";
  $lang['headline_top_performers_today'] =  "Top Performer (Heute)";
  $lang['headline_user_list'] =             "Benutzerliste";
  $lang['label_username'] =                 "Benutzername";
  $lang['role_admin'] =                     "Admin";
  $lang['role_manager'] =                   "Manager";
  $lang['role_supervisor'] =                "Supervisor";
  $lang['role_team_leader'] =               "Teamleiter";
  $lang['role_user'] =                      "User";

  // Templates
  $lang['template_success_saved'] =         "Vorlage gespeichert.";
  $lang['template_success_created'] =       "Vorlage erfolgreich erstellt.";
  $lang['template_success_deleted'] =       "Vorlage gelöscht.";

  // Password Strength (JS)
  $lang['pass_strength_weak_short'] =       "Schwach (zu kurz)";
  $lang['pass_strength_weak'] =             "Schwach";
  $lang['pass_strength_medium'] =           "Mittel";
  $lang['pass_strength_strong'] =           "Stark";
  $lang['pass_strength_very_strong'] =      "Sehr Stark";

  // 2FA
  $lang['2fa_page_title'] =                 "2FA Verifizierung";
  $lang['2fa_card_title'] =                 "Zwei-Faktor-Authentifizierung";
  $lang['2fa_instructions'] =               "Bitte geben Sie den Code aus Ihrer Authenticator-App ein.";
  $lang['2fa_error_invalid_code'] =         "Ungültiger Code. Bitte versuchen Sie es erneut.";
  $lang['2fa_button_verify'] =              "Verifizieren";
  $lang['2fa_link_cancel'] =                "Abbrechen";

  // Emails
  $lang['email_assignment_subject'] =       "Neue Aufgabe zugewiesen von %s";
  $lang['email_assignment_body'] =          "Hallo %s,\n\n%s hat dir eine Aufgabe zugewiesen.\n\nAuftrag: %s\nBemerkung: %s\n\nBitte prüfe deine Aufgabenliste.";

  // manager.php
  $lang['headline_manager_area'] =          "Manager Bereich";
  $lang['menu_announcements'] =             "Ankündigungen";
  $lang['menu_working_hours'] =             "Arbeitszeiten";
  $lang['menu_daily_goals'] =               "Tagesziele";
  $lang['menu_create_user'] =               "Benutzer erstellen";
  $lang['menu_user_list'] =                 "Benutzerliste";
  $lang['headline_system_announcement'] =   "System-Ankündigung";
  $lang['label_message'] =                  "Nachricht";
  $lang['label_type'] =                     "Typ (Farbe)";
  $lang['label_level1_blue'] =              "Stufe 1 (Blau)";
  $lang['label_level2_green'] =             "Stufe 2 (Grün)";
  $lang['label_level3_gold'] =              "Stufe 3 (Gold)";
  $lang['label_activate'] =                 "Aktivieren";
  $lang['label_date'] =                     "Datum";
  $lang['button_show'] =                    "Anzeigen";
  $lang['th_role'] =                        "Rolle";
  $lang['th_first_task'] =                  "Erste Aufgabe";
  $lang['th_last_task'] =                   "Letzte Aufgabe";
  $lang['th_timespan'] =                    "Zeitspanne";
  $lang['text_no_data_for_date'] =          "Keine Daten für dieses Datum gefunden.";
  $lang['text_open'] =                      "Offen";
  $lang['button_create'] =                  "Erstellen";
  $lang['th_last_login'] =                  "Letzter Login";
  $lang['th_actions'] =                     "Aktionen";
  $lang['status_online'] =                  "Online";
  $lang['status_offline'] =                 "Offline";
  $lang['title_unlock'] =                   "Entsperren";
  $lang['admin_success_db_update'] =        "Datenbank-Struktur erfolgreich aktualisiert (System & Benutzer).";
  $lang['admin_error_db_update'] =          "Fehler beim Datenbank-Update: ";
  $lang['confirm_really_delete'] =          "Wirklich löschen?";

  // weitere ..
  // Formularmailer
  //$lang['formmailer_label_email'] =        "E-Mail:";
  //$lang['formmailer_label_subject'] =      "Betreff:";
  //$lang['formmailer_label_message'] =      "Nachricht:";
  //$lang['formmailer_button_send'] =        "Absenden";
  //$lang['formmailer_mail_sent'] =          "Die Nachricht wurde erfolgreich versandt.";
  //$lang['formmailer_no_subject'] =         "Es wurde kein Betreff eingegeben";
