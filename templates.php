<?php

  declare(strict_types=1);

/**
  * Vorlagenverwaltung für TaskManager
  * 
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: templates.php $
  * @created $Id: 1 Montag, 9. Februar 2026, 09:57:51 GMT+0200Z ztatement $
  * 
  * @description Vorlagenverwaltung für den Aufgaben-Manager
  *
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
  */

  // Zentrale Initialisierung: Lädt Autoloader, Konfiguration, Datenbankverbindung und Sprachdateien
  require_once './includes/init.php';

  use classes\security\CsrfSecurity;

  // $taskUser wird bereits in init.php initialisiert
  $taskUser->requireLogin();
  $userId = $taskUser->getUserId();

  $csrf=new CsrfSecurity();

  $error='';
  $success='';

  // Berechtigungsprüfung für globale Vorlagen
  $canManageGlobal = $taskUser->isAdmin() || 
                     $taskUser->isManager() || 
                     $taskUser->isTeamLeader();

  $editIdString = $_GET['edit'] ?? null;

  // Formularverarbeitung
  if( $_SERVER['REQUEST_METHOD']==='POST' )
  {
    if( !$csrf->validateToken( $_POST['csrf_token']??'' ) )
    {
      $error=$lang['template_error_csrf'] ?? 'CSRF Error';
    }
    else
    {
      if( isset( $_POST['save_template'] ) )
      {
        $title=trim( $_POST['title']??'' );
        $content=$_POST['content']??'';
        $rawId=!empty( $_POST['template_id'] )?$_POST['template_id']:null;
        $isVisible=isset( $_POST['is_visible'] )?1:0;
        $isGlobal=isset( $_POST['is_global'] ) && $canManageGlobal ? true : false;
        $type = $_POST['template_type'] ?? 'user';
        
        $id = $rawId ? (int)$rawId : null;

        if( empty( $title ) )
        {
          $error=$lang['template_error_title_missing'] ?? 'Title missing';
        }
        else
        {
          $saveResult = false;
          
          if ($isGlobal)
          {
            // Global speichern
            // Sicherheit: Wenn Typ vorher 'user' war aber 'is_global' jetzt true ist -> Neu anlegen als global?
            // Vereinfachung: ID nur verwenden, wenn wir auch vorher global waren.
            $saveId = ($type === 'global') ? $id : null;
            $saveResult = $taskDb->templates->saveGlobalTemplate($title, $content, $saveId);
          }
          else
          {
            // User-spezifisch speichern
            // Wenn wir vorher 'global' editiert haben und nun 'user' speichern -> neue User-Kopie anlegen (ID null)
            $saveId = ($type === 'user' || $type === 'legacy') ? $id : null;
            $saveResult = $taskDb->templates->saveUserTemplate($userId, $title, $content, $saveId, $isVisible);
          }

          if( $saveResult )
          {
            $success=$lang['template_success_saved'] ?? 'Saved';
            if( !$id )
            { // Bei neuem Eintrag Formular leeren
              $title='';
              $content='';
              // Redirect um "Neu-Laden" Problem zu verhindern
              header( "Location: templates.php?success=created" );
              exit();
            }
          }
          else
          {
            $error=$lang['template_error_save'] ?? 'Error saving';
          }
        }
      }
      elseif( isset( $_POST['delete_template'] ) )
      {
        $rawId = $_POST['template_id']??null;
        $type = $_POST['template_type']??'user';
        $id = (int)$rawId;
        
        $deleteResult = false;
        if ($type === 'global' && $canManageGlobal)
        {
          $deleteResult = $taskDb->templates->deleteGlobalTemplate($id);
        }
        elseif ($type === 'legacy')
        {
          $deleteResult = $taskDb->templates->deleteLegacyTemplate($id, $userId);
        }
        else
        {
          $deleteResult = $taskDb->templates->deleteUserTemplate($id, $userId);
        }

        if( $id && $deleteResult )
        {
          header( "Location: templates.php?success=deleted" );
          exit();
        }
        else
        {
          $error=$lang['template_error_delete'] ?? 'Error deleting';
        }
      }
    }
  }

  if( isset( $_GET['success'] ) )
  {
    if( $_GET['success']==='created' )
      $success=$lang['template_success_created'] ?? 'Created';
    if( $_GET['success']==='deleted' )
      $success=$lang['template_success_deleted'] ?? 'Deleted';
  }

  // Header erst nach der Logik einbinden, damit Redirects funktionieren
  require_once './includes/header.php';

  // Aktuell ausgewählte Vorlage laden
  $selectedTemplate=null;

  if( $editIdString )
  {
    // Format: type_id (z.B. user_5, global_2)
    if (strpos($editIdString, '_') !== false) {
      [$type, $id] = explode('_', $editIdString);
      $selectedTemplate = $taskDb->templates->getTemplate((int)$id, $type, $userId);
    }
    else
    {
      // Fallback: Wenn kein Unterstrich vorhanden ist, ID als User-Vorlage interpretieren
      $selectedTemplate = $taskDb->templates->getTemplate((int)$editIdString, 'user', $userId);
    }
  }

  // Alle Vorlagen laden
  $templates=$taskDb->templates->getUserTemplates( $userId );

  include TEMPLATE_PATH . 'templates' . TEMPLATE_EXTENSION;

  $close_container=true;
  include './includes/footer.php';
