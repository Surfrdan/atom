<?php
class nlwCirculationPlugin {

	static function requestAllowed($resource) {
		$disallowedLevels = array(
			"Sub-sub-sub-fonds",
			"Sub-sub-fonds",
			"Sub-fonds",
			"Is-is-fonds",
			"Is-is-fonds / Sub-sub-fonds",
			"Is-is-is-fonds",
			"Fonds",
			"Is-fonds",
			"Is-fonds = Sub-fonds.",
			"Is-fonds / Sub-fonds"
		);

		$criteria = new Criteria;
    $criteria->add(QubitTerm::TAXONOMY_ID, QubitTaxonomy::LEVEL_OF_DESCRIPTION_ID);
    $criteria->addJoin(QubitTerm::ID, QubitTermI18n::ID);
    $criteria->addJoin(QubitInformationObject::LEVEL_OF_DESCRIPTION_ID, QubitTerm::ID);
    $criteria->add(QubitInformationObject::ID, $resource->getId());
    $criteria->add(QubitTermI18n::CULTURE, sfContext::getInstance()->user->getCulture());
    $term = QubitTermI18n::getOne($criteria);
		if (in_array($term->name, $disallowedLevels)) {
			return false; 
		} else {
			return true;
		}
	}

	static function getPhysicalObject($physicalObjectId) {
		$criteria = new Criteria;
    $criteria->add(QubitPhysicalObject::ID, $physicalObjectId);
    $physicalObject = QubitPhysicalObject::get($criteria)->__get(0);
		return $physicalObject;
	}

	static function getPhysicalObjects($objectId, $objects = array()) {
		$archiveCriteria = new Criteria;
    $archiveCriteria->add(QubitInformationObject::ID, $objectId);
    $resource = QubitInformationObject::get($archiveCriteria)->__get(0);	
		
		$criteria = new Criteria;
    $criteria->setDistinct();
    $criteria->addJoin(QubitRelation::OBJECT_ID, QubitInformationObject::ID);
    $criteria->addJoin(QubitRelation::SUBJECT_ID, QubitPhysicalObject::ID);
    $criteria->add(QubitRelation::TYPE_ID, QubitTerm::HAS_PHYSICAL_OBJECT_ID);
    $criteria->add(QubitRelation::OBJECT_ID, $objectId);
    $pObjects = QubitPhysicalObject::get($criteria);
		if ($pObjects->count() == 0) {
			if ($resource->getAncestors()->orderBy('lft')->count() == 0) {
				throw new Exception('No location info found for object hierachy');
			} else {
				return self::getPhysicalObjects($resource->getAncestors()->orderBy('lft')->__get(1)->id, $objects);
			}
		}
		foreach($pObjects as $physicalObject) { 
			
			$returnObject = new stdClass();
			$returnObject->referenceCode = $resource->getIdentifier();

			$returnObject->location = $physicalObject->getLocation();
			if (empty($returnObject->location)) { 
				return self::getPhysicalObjects($resource->getAncestors()->orderBy('lft')->__get(1)->id, $objects);
			}
			$returnObject->name = $physicalObject->getName();
			$returnObject->label = $physicalObject->getLabel();
			if (empty($returnObject->label)) { 
				return self::getPhysicalObjects($resource->getAncestors()->orderBy('lft')->__get(1)->id, $objects);
			}
			$returnObject->id = $physicalObject->getId();
			$objects[] = $returnObject;
		} 
		return array_filter($objects);
	}

  static function printRequest($requestId) {
    $requestCriteria = new Criteria;
    $requestCriteria->add(QubitRequest::ID, $requestId);
    $qubitRequest = QubitRequest::get($requestCriteria)->__get(0);
        
    $archiveCriteria = new Criteria;
    $archiveCriteria->add(QubitObject::ID, $qubitRequest->getObjectId());
    $resource = QubitInformationObject::get($archiveCriteria)->__get(0);
		$physicalObject = self::getPhysicalObject($qubitRequest->getPhysicalObjectId());

		$slugCriteria = new Criteria;
		$slugCriteria->add(QubitSlug::OBJECT_ID, $qubitRequest->getObjectId());
		$slugObject = QubitSlug::get($slugCriteria)->__get(0);		

    $requestSlip = array_pad(array(), 59, '');
    $requestSlip[2] = $qubitRequest->getPatronBarcode();
    $requestSlip[3] = $qubitRequest->getPatronType();
    $requestSlip[4] = $qubitRequest->getPatronName();
    $requestSlip[5] = $requestSlip[22] = $physicalObject->getLocation();
    $requestSlip[8] = $qubitRequest->getPatronNotes();
    $requestSlip[10] = date("H:i:s",strtotime($qubitRequest->getCreatedAt()));
    $requestSlip[12] = date("d-M-Y",strtotime($qubitRequest->getCreatedAt()));
    $requestSlip[13] = $slugObject->getId();
    $requestSlip[14] = 'PrintyddSlips';
    $requestSlip[15] = 'DE/SOUTH';
    $requestSlip[17] = $requestId;
    $requestSlip[21] = $resource->slug;
		$requestSlip[22] = $physicalObject->getName() . " (" . $resource->getIdentifier() . ")";
    $requestSlip[26] = date("d-M-Y",strtotime($qubitRequest->getCollectionDate()));
    $requestSlip[27] = date("H:i:s",strtotime($qubitRequest->getCollectionDate()));
		$requestSlip[39] = 'ATOM';
    $requestSlip[41] = $resource->getTitle();
    $requestSlip[42] = $qubitRequest->getItemCreator();
    $requestSlip[44] = $qubitRequest->getItemDate();
    $requestSlip[48] = ""; // Edition
    $requestSlip[52] = $resource->getCollectionRoot()->getTitle();
    $requestSlip[57] = ""; // other location
    $requestSlip[58] = ""; // other shelf numbers
    $printerSlip = implode("\n", $requestSlip);
		$ipp = new PrintIPP();
		$ipp->setHost("slipserv.llgc.org.uk");
    $ipp->setPrinterURI("/printers/AV007");
	  $ipp->setLog('/tmp/printipp','file',1);
    $ipp->setData($printerSlip);
    //echo "<pre>"; var_dump($requestSlip); echo "</pre>";
		$ipp->printJob();
	}
}
