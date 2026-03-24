<?php
/**
  *
  * TaskManager - ein einfaches und leichtes PHP Aufgaben-Management System auf Basis von PHP und SQLite
  *
  * @author Thomas Boettcher <github[at]ztatement[dot]com>
  * @copyright (c) 2026 ztatement
  *
  * @version 1.0.0.2026.03.24
  * @file $Id: index.php 1 Montag, 9. Februar 2026, 09:57:50 GMT+0200Z ztatement $
  *
  * @link https://github.com/ztatement/taskmanager
  * @package TaskManager
  *
  * @license MIT
  */

  declare(strict_types=1);

  require_once './includes/init.php';
  $taskUser->requireLogin();

  require_once './includes/header.php';

?>
  <div class="container py-5">
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
      <div class="container-fluid py-5">
        <h1 class="display-4 fw-bold fs-1"><?= $lang['index_welcome_back'] ?> <span><?= htmlspecialchars($taskUser->getUsername() ?? '') ?></span></h1>
        <h2 class="display-5 fw-bold fs-2 "><?= $lang['index_title'] ?></h2>
        <p class="col-md-8 fs-4"><?= $lang['index_subtitle'] ?></p>
        <a href="task.php" class="btn btn-primary btn-lg" type="button"><?= $lang['index_button_to_task'] ?></a>
      </div>
    </div>
  </div>

<?php require './includes/footer.php'; ?>
