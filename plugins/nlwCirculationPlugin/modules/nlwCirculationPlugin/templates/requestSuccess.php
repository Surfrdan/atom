<?php decorate_with('layout_2col'); ?>

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
    <label for="material"><?php echo __('Material') ?></label>
    <input type="text" readonly id="material" value="<?php echo implode(" ",$sf_data->getRaw('titles'));  ?>">
    <input type="hidden" id="slug" name="slug" value="<?php echo $slug; ?>" />
    <label for="collection_date"><?php echo __('Collection Date') ?></label>
    <input type="date" id="collection_date" name="collection_date" />
    <label for="notes"><?php echo __('Notes') ?></label>
    <input type="text" id="notes" name="notes" />
    <input type="submit" value ="<?php echo __('Submit Request'); ?>"/>
  </fieldset>
  
</form>

<?php end_slot() ?>