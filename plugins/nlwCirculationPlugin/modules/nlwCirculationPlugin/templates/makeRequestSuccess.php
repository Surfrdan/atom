<?php decorate_with('layout_2col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('Request Item'); ?></h1>
<?php end_slot() ?>

<?php slot('content') ?>

<?php echo $sf_request->getParameter('shib_user'); ?>
<p>Done</p>
<?php end_slot() ?>