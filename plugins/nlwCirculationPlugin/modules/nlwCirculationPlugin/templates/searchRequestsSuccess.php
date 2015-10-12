<?php decorate_with('layout_2col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('Request Item'); ?></h1>
<?php end_slot() ?>



<?php slot('content') ?>

<form action="<?php echo url_for(array($resource, 'module' => 'nlwCirculationPlugin', 'action' => 'editRequest')) ?>" method="post">
  <fieldset id="requestInformation">
    <legend><?php echo __('Search Requests') ?></legend>
    <label for="material"><?php echo __('Request ID') ?></label>
    <input type="text" id="request_id" name="request_id" value="" autofocus />
    <input type="submit" value ="<?php echo __('Submit Request'); ?>"/>
  </fieldset>
  
</form>

<?php end_slot() ?>
