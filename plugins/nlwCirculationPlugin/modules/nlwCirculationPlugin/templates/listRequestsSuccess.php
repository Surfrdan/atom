<?php decorate_with('layout_2col'); ?>

<?php slot('sidebar') ?>

<?php slot('title') ?>
  <h1><?php echo __('List Requests'); ?></h1>
<?php end_slot() ?>

<script>
</script>

<?php slot('content') ?>
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
        <td><?php echo link_to(__('View'), array('module' => 'nlwCirculationPlugin', 'action' => 'editRequest', 'request_id' => $r->getId(), )) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    </table>
<?php end_slot() ?>
