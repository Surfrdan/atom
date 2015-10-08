<?php decorate_with('layout_2col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('Edit Request'); ?></h1>
<?php end_slot() ?>



<?php slot('content') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  $(function() {
    $( "#collection_date" ).datepicker({
      defaultDate: "<?php if($staff) { echo $qubitRequest->getCollectionDate(); } else { echo "+7"; } ?>" ,
      constrainInput: false,
      <?php if($staff) { ?> maxDate: "+30", <?php } else { ?> maxDate: "+7", <?php } ?>
      minDate: "-0",
      dateFormat: "yy-mm-dd"
    });
  });

  <?php if($staff) { ?>   
  $(function() {
    $( "#expiry_date" ).datepicker({
      defaultDate: "<?php echo $qubitRequest->getExpiryDate(); ?>" ,
      constrainInput: false,
      minDate: "-0",
      dateFormat: "yy-mm-dd"
    });
  });
  <?php } ?>

</script>
<form action="<?php echo url_for(array($resource, 'module' => 'nlwCirculationPlugin', 'action' => 'makeRequest', 'slug' => $slug)) ?>" method="post">
  <fieldset id="requestInformation">
    <legend><?php echo __('Request Information') ?></legend>
    <?php if($staff) { ?> 
    <label for="request_id"><?php echo __('Request ID') ?></label>
    <input type="text" id="request_id" name="request_id" value="<?php if($staff) { echo $qubitRequest->getId(); } ?>" readonly />
    <label for="request_date"><?php echo __('Request Date') ?></label>
    <input type="text" id="request_date" name="request_date" value="<?php if($staff) { echo $qubitRequest->getCreatedAt(); } ?>" readonly />
    <label for="expiry_date"><?php echo __('Expiry Date') ?></label>
    <input type="date" id="expiry_date" name="expiry_date" value="<?php if($staff) { echo $qubitRequest->getExpiryDate(); } ?>" />
    <label for="patron_barcode"><?php echo __('Patron Barcode') ?></label>
    <input type="text" id="patron_barcode" name="patron_barcode" value="<?php if($staff) { echo $qubitRequest->getPatronBarcode(); } ?>" readonly />
    <?php } ?>
    
    <label for="material"><?php echo __('Material') ?></label>
    <?php echo link_to(render_title($resource), array($resource, 'module' => 'informationobject')) ?>
    <br /><br />
    <input type="hidden" id="slug" name="slug" value="<?php echo $slug; ?>" />
    <label for="collection_date"><?php echo __('Collection Date') ?></label>
    <input type="date" id="collection_date" name="collection_date" value="<?php if($staff) { echo $qubitRequest->getCollectionDate(); } ?>" />
    <label for="patron_notes"><?php echo __('Notes') ?></label>
    <input type="text" id="patron_notes" name="patron_notes" value="<?php if($staff) { echo $qubitRequest->getPatronNotes(); } ?>" />
    <?php if($staff) { ?> 
    <label for="staff_notes"><?php echo __('Staff Notes') ?></label>
    <input type="text" id="staff_notes" name="staff_notes" value="<?php if($staff) { echo $qubitRequest->getStaffNotes(); } ?>" />
    <?php } ?>
    <input type="submit" value ="<?php echo __('Update Request'); ?>"/>
  </fieldset>
  
</form>

<?php end_slot() ?>