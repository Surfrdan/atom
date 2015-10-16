<?php decorate_with('layout_1col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('List Requests'); ?></h1>
<?php end_slot() ?>

<?php slot('content') ?>
<form action="<?php echo url_for(array('module' => 'nlwCirculationPlugin', 'action' => 'listRequests')) ?>" method="post">
  <fieldset id="request_statuses">
    <label for="cancelled"><?php echo __('Cancelled') ?></label>
    <input type="checkbox" name="request_statuses[]" value="5" <?php if (in_array(5, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?> >
    <label for="current"><?php echo __('Current') ?></label>
    <input type="checkbox" name="request_statuses[]" value="1" <?php if (in_array(1, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
    <label for="awaiting"><?php echo __('Awaiting Collection') ?></label>
    <input type="checkbox" name="request_statuses[]" value="2" <?php if (in_array(2, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
    <label for="issued"><?php echo __('Issued to Reader') ?></label>
    <input type="checkbox" name="request_statuses[]" value="3" <?php if (in_array(3, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
    <label for="returned"><?php echo __('Returned to Stacks') ?></label>
    <input type="checkbox" name="request_statuses[]" value="4" <?php if (in_array(4, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
		<input type="submit">
  </fieldset>
</form>

    <table id="requestTable" name="requestTable" class="tablesorter">
      <thead>
        <tr>
          <th><?php echo __('Patron') ?></th>
          <th><?php echo __('Requested') ?></th>
          <th><?php echo __('Collection') ?></th>
          <th><?php echo __('Status') ?></th>
          <th><?php echo __('View') ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($qubitRequests as $r):  ?>
      <tr>
        <td><?php echo $r->getPatronBarcode(); ?></td>
        <td><?php echo $r->getCreatedAt(); ?></td>
        <td><?php echo $r->getCollectionDate(); ?></td>
        <td><?php echo $r->getStatus(); ?></td>
        <td><?php echo link_to(__('View'), array('module' => 'nlwCirculationPlugin', 'action' => 'editRequest', 'request_id' => $r->getId())) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    </table>
<?php end_slot() ?>
