<?php decorate_with('layout_1col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('Item Request Complete'); ?></h1>
<?php echo __('Return to requested object: '); ?>
<?php echo link_to(render_title($resource), array($resource, 'module' => 'informationobject')) ?>
<?php end_slot() ?>

<?php slot('content') ?>
<?php end_slot() ?>
