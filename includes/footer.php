<?php
/**
  * Footer für TaskManager
  * 
  * @author Thomas Boettcher @ztatement (github[at]ztatement[dot]com)
  * @copyright (c) 2026 ztatement
  * 
  * @version 1.0.0.2026.03.24
  * @file $Id: footer.php $
  * @created $Id: 1 Montag, 9. Februar 2026, 09:57:55 GMT+0200Z ztatement $
  *
  * @description Footer für den Aufgaben- und Projektmanagementbereich
  * 
  * @repository https://github.com/ztatement/taskmanager
  * @license MIT (https://opensource.org/license/MIT)
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

  <script nonce="<?= CSP_NONCE ?>" src="<?= BOOTSTRAP_JS ?>"></script>
  <?php if ($showChat): ?>
  <script nonce="<?= CSP_NONCE ?>" src="<?= JS_URL ?>chat.js"></script>
  <?php endif; ?>
  <script nonce="<?= CSP_NONCE ?>" src="<?= JS_URL ?>notes.js"></script>
  <script nonce="<?= CSP_NONCE ?>">
  // Automatisches Mitsenden des CSRF-Tokens für alle AJAX-Anfragen
  (function() {
    const isInternal = (url) => {
      if (!url) return true;
      return !url.startsWith('http') || url.startsWith(window.location.origin);
    };

    // 1. Interceptor für die moderne Fetch-API
    const originalFetch = window.fetch;
    window.fetch = function(resource, config = {}) {
      const method = config.method ? config.method.toUpperCase() : 'GET';
      const url = typeof resource === 'string' ? resource : resource.url;

      if (['POST', 'PUT', 'DELETE'].includes(method) && isInternal(url)) {
        config.headers = config.headers || {};
        if (config.headers instanceof Headers) {
          if (!config.headers.has('X-CSRF-Token')) config.headers.set('X-CSRF-Token', window.taskConfig.csrfToken);
          config.headers.set('X-CSP-Nonce', window.taskConfig.cspNonce);
        } else {
          if (!config.headers['X-CSRF-Token']) config.headers['X-CSRF-Token'] = window.taskConfig.csrfToken;
          config.headers['X-CSP-Nonce'] = window.taskConfig.cspNonce;
        }
      }
      // Nonce auch für GET-Requests mitsenden (wichtig für Template-Nachladen)
      if (method === 'GET' && isInternal(url)) {
        config.headers = config.headers || {};
        if (config.headers instanceof Headers) {
            config.headers.set('X-CSP-Nonce', window.taskConfig.cspNonce);
        } else {
            config.headers['X-CSP-Nonce'] = window.taskConfig.cspNonce;
        }
      }
      return originalFetch(resource, config);
    };

    // 2. Interceptor für klassische XMLHttpRequests (z.B. jQuery oder Vanilla AJAX)
    const originalOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(method, url) {
      this._method = method;
      this._url = url;
      return originalOpen.apply(this, arguments);
    };

    const originalSend = XMLHttpRequest.prototype.send;
    XMLHttpRequest.prototype.send = function() {
      const method = (this._method || '').toUpperCase();
      if (['POST', 'PUT', 'DELETE'].includes(method) && isInternal(this._url)) {
        this.setRequestHeader('X-CSRF-Token', window.taskConfig.csrfToken);
      }
      return originalSend.apply(this, arguments);
    };

    })();
  </script>
 </body>
</html>