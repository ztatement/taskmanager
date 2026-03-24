<?php
/**
  * Footer für TaskManager
  * 
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: footer.php 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  * 
  * @link https://github.com/ztatement/taskmanager
  * 
  * @license MIT
  * 
  * @category Footer
  * @package TaskManager
  * 
  * @description Footer Template
  */

/**
 * @var \classes\security\CsrfSecurity $csrf
 */
  use classes\security\CsrfSecurity;

  if( $close_container??false ): 
?>
      </div>
    </div> <!-- Schließt den Container aus header.php -->
  <?php endif;?>
<?php
  // Chat-Berechtigungen prüfen
  $chatSetting = $taskDb->settings->getSetting('chat_active');
  $isChatActive = (!isset($chatSetting['value']) || $chatSetting['value'] === '1');
  $userRole = $taskUser->getRole();

  // Chat anzeigen, wenn User kein einfacher User ist UND (Chat aktiv ist ODER User Admin ist)
  $showChat = ($userRole !== 'user' && ($isChatActive || $userRole === 'admin'));

  if ($showChat)
  {
    include __DIR__ . '/chat_widget.php';
  }

  include __DIR__ . '/module/zst_rechner.php';
?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./static/js/color-modes.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if ($showChat): ?>
  <script src="./static/js/chat.js"></script>
  <?php endif; ?>
  <script src="./static/js/notes.js"></script>
  <script>
    <?php 
    // Sicherstellen, dass ein CSRF-Token verfügbar ist, auch wenn die Seite selbst keines initialisiert hat
    if (!isset($csrf)) { $csrf = new CsrfSecurity(); }
    if (isset($csrf)):
    ?>
    if (typeof csrfToken === 'undefined') { var csrfToken = '<?= $csrf->getToken() ?>'; }
    <?php endif; ?>
  </script>
  <script>
    // Globales Dark-Theme für alle SweetAlert2 Popups setzen
    if (typeof Swal !== 'undefined') {
      window.Swal = Swal.mixin({
        /*  background: '< ?= defined("SWAL_BACKGROUND") ? SWAL_BACKGROUND : "#212529" ?>',
          color: '< ?= defined("SWAL_COLOR") ? SWAL_COLOR : "#f8f9fa" ?>',*/
        confirmButtonColor: '<?= defined("SWAL_CONFIRM_BUTTON_COLOR") ? SWAL_CONFIRM_BUTTON_COLOR : "#85085fff" ?>',
        cancelButtonColor: '<?= defined("SWAL_CANCEL_BUTTON_COLOR") ? SWAL_CANCEL_BUTTON_COLOR : "#6c757d" ?>'
      });
    }
  </script>
 </body>
</html>