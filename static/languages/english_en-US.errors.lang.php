<?php
/**
  * General English translations of the language variables
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24 $Id:
  * @file static/languages/english_en-US.errors.lang.php 1 Montag, 16. Februar 2026, 12:35:10 GMT+0100Z ztatement $
  *
  * @description Error language file for the English translation
  * @lastModified 2026-03-24 12:35:10 GMT+0100Z
  * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  $lang['error'] = "Error";

  // Login Errors
  $lang['login_error_timeout'] = "You have been automatically logged out due to inactivity.";
  $lang['login_error_forced_logout'] = "Your permissions or account details have been changed. Please log in again.";
  $lang['register_error_disabled'] =        "Registration is currently disabled.";
  $lang['register_error_domain_not_allowed'] = "This email domain is not allowed for registration.";

  // Reset Password Errors
  $lang['reset_password_error_csrf'] = "Invalid CSRF token. Please reload the page.";
  $lang['reset_password_error_mismatch'] = "The passwords do not match or are empty.";
  $lang['reset_password_error_invalid_token'] = "The reset link is invalid or has expired.";
  $lang['reset_password_error_username_in_password'] = "The password must not contain the username.";
  $lang['reset_password_error_common_password'] = "The password is too simple and is on the blocklist (e.g., \"Password123\").";
  $lang['reset_password_error_breached_password'] = "This password was found in a known data breach. Please choose a more secure password.";
  $lang['reset_password_error_history'] = "You cannot reuse one of your last 3 passwords.";

  // Profile Errors
  $lang['profile_error_csrf'] = "Invalid CSRF token. Please reload the page.";
  $lang['profile_error_delete_account'] = "Error deleting account.";
  $lang['profile_error_2fa_invalid_code'] = "Invalid code. Setup failed.";
  $lang['profile_error_empty_fields'] = "Username and E-Mail are required.";
  $lang['profile_error_invalid_email'] = "Please enter a valid email address.";
  $lang['profile_error_username_exists'] = "This username is already taken.";
  $lang['profile_error_email_exists'] = "This email address is already in use.";
  $lang['profile_error_password_mismatch'] = "The passwords do not match.";
  $lang['profile_error_password_too_short'] = "The password must be at least 8 characters long.";
  $lang['profile_error_password_contains_username'] = "The password must not contain the username.";
  $lang['profile_error_password_common'] = "The password is too simple and is on the blocklist (e.g., \"Password123\").";
  $lang['profile_error_password_in_history'] = "You cannot reuse one of your last 3 passwords.";
  $lang['profile_error_password_breached'] = "This password was found in a known data breach. Please choose a more secure password.";
  $lang['profile_error_saving'] = "Error saving data.";

  // Profile Confirmations (JS)
  $lang['profile_js_confirm_disable_2fa_title'] = 'Really disable 2FA?';
  $lang['profile_js_confirm_disable_2fa_text'] = 'Your account will be less secure.';
  $lang['profile_js_confirm_disable_2fa_button'] = 'Yes, disable';
  $lang['profile_js_confirm_delete_account_title'] = 'Really delete account?';
  $lang['profile_js_confirm_delete_account_text'] = 'Your user account will be permanently deleted. This action cannot be undone!';
  $lang['profile_js_confirm_delete_account_text_tasks'] = ' All your created tasks will also be irrevocably deleted.';
  $lang['profile_js_confirm_delete_account_button'] = 'Yes, delete permanently';
  $lang['profile_js_button_cancel'] = 'Cancel';

  // Task Errors
  $lang['task_error_manager_limit'] = "Managers are not allowed to edit tasks older than the last month.";
  $lang['task_error_supervisor_limit'] = "Supervisors are not allowed to edit tasks older than yesterday.";
  $lang['task_error_team_leader_limit'] = "Team Leaders are not allowed to edit tasks older than last week.";
  $lang['task_error_edit_too_old'] = "Editing not possible: The task is older than yesterday.";
  $lang['task_error_future_date'] = "The backdating date cannot be in the future.";
  $lang['task_error_update_failed'] = "Error during update.";
  $lang['task_error_db_error'] = "Database error.";
  $lang['task_error_manager_delete_limit'] = "Managers are not allowed to delete tasks older than the last month.";
  $lang['task_error_team_leader_delete_limit'] = "Team Leaders are not allowed to delete tasks older than last week.";
  $lang['task_error_delete_too_old'] = "Deletion not possible: The task is older than yesterday.";
  $lang['task_error_delete_failed'] = "Error during deletion.";
  $lang['task_error_not_found'] = "Task not found.";

  // Template Errors
  $lang['template_error_csrf'] = "Invalid CSRF token. Please reload the page.";
  $lang['template_error_title_missing'] = "Please enter a title.";
  $lang['template_error_save'] = "Error while saving.";
  $lang['template_error_delete'] = "Error while deleting.";

  // Day Message Errors
  $lang['day_msg_error_invalid_type'] = "Invalid file type. Allowed: %s";
  $lang['day_msg_error_too_large'] = "File is too large (max. %s).";
  $lang['day_msg_error_upload_move'] = "Error moving the file.";

  // Admin Errors
  $lang['admin_error_csrf'] = "Invalid CSRF token. Please reload the page.";
  $lang['admin_error_missing_fields'] = "All fields are required.";
  $lang['admin_error_username_taken'] = "Username already taken.";
  $lang['admin_error_key_spaces'] = "The new key must not contain spaces.";
  $lang['admin_error_save_new'] = "Error saving the new entry.";
  $lang['admin_error_no_key'] = "No key specified for deletion.";
  $lang['admin_error_readonly'] = "The setting '%s' is read-only and cannot be deleted.";
  $lang['admin_error_setting_delete'] = "Error deleting setting '%s'.";
  $lang['admin_error_user_update'] = "Error updating user.";
