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

    if($user->hasGroup(99)) {
      if($request->getParameter('request_id')) {
        $requestId = $request->getParameter('request_id');
        $requestCriteria = new Criteria;
        $requestCriteria->add(QubitRequest::ID, $requestId);
        $this->qubitRequest = QubitRequest::get($requestCriteria)->__get(0);
        
        $archiveCriteria = new Criteria;
        $archiveCriteria->add(QubitObject::ID, $this->qubitRequest->getObjectId());
        $slug = $this->resource->slug;
        $this->resource = QubitInformationObject::get($archiveCriteria)->__get(0);
        
        $requestSlip = array_pad(array(), 59, '');
        $requestSlip[2] = $this->qubitRequest->getPatronBarcode();
        $requestSlip[3] = $this->qubitRequest->getPatronType();
        $requestSlip[4] = $this->qubitRequest->getPatronName();
        $requestSlip[5] = "ARCH/MSS (GB0210)";
        $requestSlip[8] = $this->qubitRequest->getPatronNotes();
        $requestSlip[10] = date("H:i:s",strtotime($this->qubitRequest->getCreatedAt()));
        $requestSlip[12] = date("d-M-Y",strtotime($this->qubitRequest->getCreatedAt()));
        $requestSlip[13] = $this->resource->getPropertyByName('Control number')->getvalue();
        $requestSlip[14] = 'PrintyddSlips';
        $requestSlip[15] = 'DE/SOUTH';
        $requestSlip[17] = 'TODO: Request';
        $requestSlip[21] = $this->resource->getPropertyByName('Control number')->getvalue();
        $requestSlip[22] = 'TODO: Shelf Number';
        $requestSlip[26] = date("d-M-Y",strtotime($this->qubitRequest->getCollectionDate()));
        $requestSlip[27] = date("H:i:s",strtotime($this->qubitRequest->getCollectionDate()));
        $requestSlip[41] = $this->resource->getTitle();
        $requestSlip[42] = "TODO: Creator";
        $requestSlip[44] = "TODO: Date";
        $requestSlip[48] = "TODO: Edition";
        $requestSlip[52] = $this->resource->getCollectionRoot()->getTitle();
        $requestSlip[57] = "TODO: Other Location";
        $requestSlip[58] = "TODO: Other Shelf Numbers";
        var_dump($requestSlip);
        //var_dump($this->resource);
      }
    }
    
      /*
    $this->titles = array($this->resource->__toString());
    $noparent = false;
    $object = $this->resource;
    while ($noparent == false) {
      if (isset($object->parent) && $object->parent->__toString() != '') {
        $object = $object->parent;
        $this->titles[] = ($object->__toString());
      } else {
        $noparent = true;
      }
    }
    $this->titles = array_reverse($this->titles); 
    
    $this->materialTitle = implode(" ",$this->titles); 
    */
    // go back to refferer?
    // $this->redirect(array($resource, 'module' => 'nlwCirculationPlugin', 'action' => 'updateRequest'));
  }
  
  
  /*
  public function executeUpdate($request) {
    
    $this->qubitRequest = new QubitRequest();
    $this->qubitRequest->set
  }
  */
  
}
