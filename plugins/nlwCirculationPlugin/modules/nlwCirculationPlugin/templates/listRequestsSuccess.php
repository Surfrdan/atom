<?php decorate_with('layout_1col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('List Requests'); ?></h1>
<?php end_slot() ?>

<?php slot('content') ?>
<form action="<?php echo url_for(array('module' => 'nlwCirculationPlugin', 'action' => 'listRequests')) ?>" method="post">
  <fieldset id="request_statuses">
				<ul class="checkbox-grid">
					<li>
						<input type="checkbox" name="request_statuses[]" value="5" <?php if (in_array(5, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?> >
						<label for="cancelled"><?php echo __('Cancelled') ?></label>
					</li>
					<li>
						<input type="checkbox" name="request_statuses[]" value="1" <?php if (in_array(1, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
						<label for="current"><?php echo __('Current') ?></label>
					</li>
					<li>
						<input type="checkbox" name="request_statuses[]" value="2" <?php if (in_array(2, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
						<label for="awaiting"><?php echo __('Awaiting Collection') ?></label>
					</li>
					<li>
						<input type="checkbox" name="request_statuses[]" value="3" <?php if (in_array(3, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
						<label for="issued"><?php echo __('Issued to Reader') ?></label>
					</li>
					<li>
						<input type="checkbox" name="request_statuses[]" value="4" <?php if (in_array(4, $sf_data->getRaw('request_statuses'))) { echo "checked"; } ?>>
						<label for="returned"><?php echo __('Returned to Stacks') ?></label>
					</li>
				</ul>

		<input type="submit">
  </fieldset>
</form>
    <table id="requestTable" name="requestTable" class="tablesorter">
      <thead>
        <tr>
          <th><?php echo __('ID') ?></th>
          <th><?php echo __('Date') ?></th>
          <th><?php echo __('Barcode') ?></th>
          <th><?php echo __('Name') ?></th>
          <th><?php echo __('Item') ?></th>
          <th><?php echo __('Collect') ?></th>
          <th><?php echo __('Expires') ?></th>
          <th><?php echo __('Status') ?></th>
          <th><?php echo __('View') ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($qubitRequests as $r):  ?>
      <tr>
        <td><?php echo $r->getId(); ?></td>
        <td><?php echo $r->getCreatedAt(); ?></td>
        <td><?php echo $r->getPatronBarcode(); ?></td>
        <td><?php echo $r->getPatronName(); ?></td>
        <td><?php echo $r->getObjectId(); ?></td>
        <td><?php echo $r->getCollectionDate(); ?></td>
        <td><?php echo $r->getExpiryDate(); ?></td>
        <td><?php echo $statuses[$r->getStatus()]; ?></td>
        <td><?php echo link_to(__('View'), array('module' => 'nlwCirculationPlugin', 'action' => 'editRequest', 'request_id' => $r->getId())) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    </table>
<?php end_slot() ?>
