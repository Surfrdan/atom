<?php decorate_with('layout_2col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('Request Item'); ?></h1>
<?php end_slot() ?>

<?php slot('content') ?>

<form action="" method="post">
  <fieldset id="requestInformation">
    <legend><?php echo __('Request Information') ?></legend>
    
    <input type="hidden" id="slug" value="<?php echo $slug; ?>" />
    <input type="hidden" id="shib_user" value="<?php echo $_SERVER['REMOTE_USER']; ?>" />
    <label for="material"><?php echo __('Material Information') ?></label>
    <input type="text" id="material" />
    <label for="collection_date"><?php echo __('Collection Date') ?></label>
    <input type="date" id="collection_date "/>
    <label for="notes"><?php echo __('Notes') ?></label>
    <input type="text" id="notes "/>
      
    </div>
  </fieldset>
  
  


</form>
<?php end_slot() ?>