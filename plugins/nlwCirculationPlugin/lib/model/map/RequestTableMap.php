<?php


/**
 * This class defines the structure of the 'request' table.
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
class RequestTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.nlwCirculationPlugin.lib.model.map.RequestTableMap';

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
		$this->setName('request');
		$this->setPhpName('request');
		$this->setClassname('QubitRequest');
		$this->setPackage('plugins.nlwCirculationPlugin.lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'id', 'INTEGER', true, null, null);
		$this->addForeignPrimaryKey('OBJECT_ID', 'objectId', 'INTEGER' , 'object', 'ID', true, null, null);
		$this->addForeignKey('REQUEST_TYPE_ID', 'requestTypeId', 'INTEGER', 'request_type', 'ID', false, null, null);
		$this->addColumn('PATRON_BARCODE', 'patronBarcode', 'VARCHAR', false, 255, null);
		$this->addColumn('COLLECTION_DATE', 'collectionDate', 'DATE', false, null, null);
		$this->addColumn('EXPIRY_DATE', 'expiryDate', 'DATE', false, null, null);
		$this->addColumn('PATRON_NOTES', 'patronNotes', 'LONGVARCHAR', false, null, null);
		$this->addColumn('PATRON_TYPE', 'patronType', 'VARCHAR', false, 255, null);
		$this->addColumn('PATRON_NAME', 'patronName', 'VARCHAR', false, 255, null);
		$this->addColumn('STAFF_NOTES', 'staffNotes', 'LONGVARCHAR', false, null, null);
		$this->addForeignKey('STATUS', 'status', 'INTEGER', 'request_status', 'ID', false, null, null);
		$this->addColumn('CREATED_AT', 'createdAt', 'TIMESTAMP', true, null, null);
		$this->addColumn('UPDATED_AT', 'updatedAt', 'TIMESTAMP', true, null, null);
		$this->addColumn('SERIAL_NUMBER', 'serialNumber', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('object', 'object', RelationMap::MANY_TO_ONE, array('object_id' => 'id', ), 'CASCADE', null);
    $this->addRelation('requestType', 'requestType', RelationMap::MANY_TO_ONE, array('request_type_id' => 'id', ), null, null);
    $this->addRelation('requestStatus', 'requestStatus', RelationMap::MANY_TO_ONE, array('status' => 'id', ), null, null);
	} // buildRelations()

} // RequestTableMap
