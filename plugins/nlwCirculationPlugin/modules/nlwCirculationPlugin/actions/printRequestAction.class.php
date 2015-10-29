<?php
/*
 * This file is part of the Access to Memory (AtoM) software.
 *
 * Access to Memory (AtoM) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Access to Memory (AtoM) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Access to Memory (AtoM).  If not, see <http://www.gnu.org/licenses/>.
 */

class nlwCirculationPluginPrintRequestAction extends sfAction
{
  public function execute($request)
  {
    $user = $this->getUser();
		if (!$this->getUser()->isAuthenticated()) {
      QubitAcl::forwardUnauthorized();
    }
    $requestId = $request->getParameter('request_id');
    $requestCriteria = new Criteria;
    $requestCriteria->add(QubitRequest::ID, $requestId);
    $this->qubitRequest = QubitRequest::get($requestCriteria)->__get(0);
        
    $archiveCriteria = new Criteria;
    $archiveCriteria->add(QubitObject::ID, $this->qubitRequest->getObjectId());
    $this->resource = QubitInformationObject::get($archiveCriteria)->__get(0);
     
    $criteria = new Criteria;
    $criteria->setDistinct();
    $criteria->add(QubitRelation::TYPE_ID, QubitTerm::HAS_PHYSICAL_OBJECT_ID);
    $criteria->addJoin(QubitRelation::OBJECT_ID, QubitInformationObject::ID);
    $criteria->addJoin(QubitRelation::SUBJECT_ID, QubitPhysicalObject::ID);
    $this->physicalObjects = QubitPhysicalObject::get($criteria);
		var_dump($this->physicalObjects->__get(0)->getName()); exit;
   

    $physCriteria = new Criteria;
    $physCriteria->add(QubitRelation::OBJECT_ID, $this->resource->getCollectionRoot()->getId());
    $physCriteria->add(QubitRelation::TYPE_ID, QubitTerm::HAS_PHYSICAL_OBJECT_ID);
    $physCriteria->addJoin(QubitRelation::SUBJECT_ID, QubitPhysicalObject::ID);
		$this->phys = QubitPhysicalObject::get($physCriteria);
		var_dump($this->phys->count());
	 	echo "object: " . $this->qubitRequest->getObjectId() . "<br />";
		echo "collection object: " . $this->resource->getCollectionRoot()->getId() . "<br />";
    $requestSlip = array_pad(array(), 59, '');
    $requestSlip[2] = $this->qubitRequest->getPatronBarcode();
    $requestSlip[3] = $this->qubitRequest->getPatronType();
    $requestSlip[4] = $this->qubitRequest->getPatronName();
    $requestSlip[5] = $this->phys->getLocation();
    $requestSlip[8] = $this->qubitRequest->getPatronNotes();
    $requestSlip[10] = date("H:i:s",strtotime($this->qubitRequest->getCreatedAt()));
    $requestSlip[12] = date("d-M-Y",strtotime($this->qubitRequest->getCreatedAt()));
    $requestSlip[13] = $request->getParameter('request_id');
    $requestSlip[14] = 'PrintyddSlips';
    $requestSlip[15] = 'DE/SOUTH';
    $requestSlip[17] = $request->getParameter('request_id');
    $requestSlip[21] = $this->resource->slug;
    $requestSlip[22] = $this->phys->getName();
    $requestSlip[26] = date("d-M-Y",strtotime($this->qubitRequest->getCollectionDate()));
    $requestSlip[27] = date("H:i:s",strtotime($this->qubitRequest->getCollectionDate()));
    $requestSlip[41] = $this->resource->getTitle();
    $requestSlip[42] = $this->qubitRequest->getItemCreator();
    $requestSlip[44] = date("H:i:s",strtotime($this->qubitRequest->getCollectionDate()));
    $requestSlip[48] = "TODO: Edition";
    $requestSlip[52] = $this->resource->getCollectionRoot()->getTitle();
    $requestSlip[57] = ""; // other location
    $requestSlip[58] = "TODO: Other Shelf Numbers";
    $printerSlip = implode("\n", $requestSlip);
		var_dump($requestSlip);
		exit;
		$ipp = new PrintIPP();
		$ipp->setHost("slipserv.llgc.org.uk");
    $ipp->setPrinterURI("/printers/AV007");
	  $ipp->setLog('/tmp/printipp','file',1);
    $ipp->setData($printerSlip);
    $ipp->printJob();
  }
}
