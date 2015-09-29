<?php


/**
 * This class defines the structure of the 'request_statuses' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    plugins.nlwCirculationPlugin.lib.model.map
 */
class RequestStatusesTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.nlwCirculationPlugin.lib.model.map.RequestStatusesTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('request_statuses');
		$this->setPhpName('requestStatuses');
		$this->setClassname('QubitRequestStatuses');
		$this->setPackage('plugins.nlwCirculationPlugin.lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addPrimaryKey('ID', 'id', 'INTEGER', true, null, null);
		$this->addColumn('STATUS', 'status', 'VARCHAR', false, 255, null);
		$this->addColumn('SERIAL_NUMBER', 'serialNumber', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('requests', 'requests', RelationMap::ONE_TO_MANY, array('id' => 'status', ), null, null);
	} // buildRelations()

} // RequestStatusesTableMap
