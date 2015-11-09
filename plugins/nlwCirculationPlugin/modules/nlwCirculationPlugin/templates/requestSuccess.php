<?php decorate_with('layout_1col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('Request Item'); ?></h1>
<?php end_slot() ?>



<?php slot('content') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  $(function() {
    $( "#collection_date" ).datepicker({
      defaultDate: +7,
      constrainInput: false,
      maxDate: "+7",
      minDate: "-0",
      dateFormat: "yy-mm-dd"
    });
  });
</script>
<form action="<?php echo url_for(array($resource, 'module' => 'nlwCirculationPlugin', 'action' => 'makeRequest', 'slug' => $slug)) ?>" method="post">
  <fieldset id="requestInformation">
    <legend><?php echo __('Request Information') ?></legend>
		<?php $user = $sf_data->getRaw('user'); ?>
		<?php if ($user->getAttribute('employeeType') == 'STAFF') { ?>
    <label for="patron_barcode"><?php echo __('Patron Barcode'); ?></label>
    <input type="text" id="patron_barcode" name="patron_barcode" value="<?php echo $user->getAttribute('employeeNumber'); ?>"/>
 		<?php } ?>
    <label for="material"><?php echo __('Material') ?></label>
    <input type="text" readonly id="material" value="<?php echo implode(" ",$sf_data->getRaw('titles'));  ?>">
    <input type="hidden" id="slug" name="slug" value="<?php echo $slug; ?>" />
    <label for="location"><?php echo __('Location') ?></label>
    <select id="location" name="location"  >
      <?php foreach ($physicalObjects as $l) {?>
      <option value="<?php echo $l->id; ?>"> <?php echo $l->label; ?></option>
      <?php } ?>
    </select>


    <label for="collection_date"><?php echo __('Collection Date') ?></label>
    <input type="date" id="collection_date" name="collection_date" />
    <label for="notes"><?php echo __('Notes') ?></label>
    <input type="text" id="notes" name="notes" />
    <label for="expiry_date"><?php echo __('Expiry Date') ?></label>
    <input type="date" id="expiry_date" name="expiry_date" readonly value="<?php echo date("Y-m-d",strtotime("+1 week")); ?>" />
 
    <input type="submit" value ="<?php echo __('Submit Request'); ?>" class="request-button" />
  </fieldset>
  
</form>

<?php end_slot() ?>
