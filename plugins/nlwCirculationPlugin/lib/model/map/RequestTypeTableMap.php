<?php


/**
 * This class defines the structure of the 'request_type' table.
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
class RequestTypeTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.nlwCirculationPlugin.lib.model.map.RequestTypeTableMap';

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
		$this->setName('request_type');
		$this->setPhpName('requestType');
		$this->setClassname('QubitRequestType');
		$this->setPackage('plugins.nlwCirculationPlugin.lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'id', 'INTEGER', true, null, null);
		$this->addColumn('TYPE', 'type', 'VARCHAR', false, 255, null);
		$this->addColumn('SERIAL_NUMBER', 'serialNumber', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('request', 'request', RelationMap::ONE_TO_MANY, array('id' => 'request_type_id', ), null, null);
	} // buildRelations()

} // RequestTypeTableMap
