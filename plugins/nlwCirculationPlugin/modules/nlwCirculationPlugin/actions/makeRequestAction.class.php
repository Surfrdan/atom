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
class nlwCirculationPluginMakeRequestAction extends sfAction
{
  public function execute($request)
  {
    $user = $this->getUser();
    $path = $request->getPathInfo();
    $pieces = explode("/", $path, 3);
    $slug = $request->getParameter('slug');
   
    $criteria = new Criteria;
    $criteria->add(QubitSlug::SLUG, $slug);
    $criteria->addJoin(QubitSlug::OBJECT_ID, QubitObject::ID);
    $this->resource = QubitObject::get($criteria)->__get(0);
		$this->creator = $this->resource->getCollectionRoot()->getCreators()->__get(0); 
		$this->event = $this->resource->getDates()->__get(0);
		$this->qubitRequest = new QubitRequest();
    $this->qubitRequest->setObjectId($this->resource->id);
    $this->qubitRequest->setRequestTypeId('1');
    $this->qubitRequest->setStatus('1');
    $this->qubitRequest->setPhysicalObjectId($request->getParameter('location'));
    $this->qubitRequest->setExpiryDate(date("Y-m-d",strtotime("+1 week")));
		if ($request->getParameter('patron_barcode')) {
			$this->qubitRequest->setPatronBarcode($request->getParameter('patron_barcode'));
			$this->qubitRequest->setRequesterBarcode($user->getAttribute('employeeNumber'));
		} else {
    	$this->qubitRequest->setPatronBarcode($user->getAttribute('employeeNumber'));
		}
    $this->qubitRequest->setPatronType($user->getAttribute('employeeType'));
    $this->qubitRequest->setPatronName($user->getAttribute('employeeName'));
    $this->qubitRequest->setCollectionDate($request->getParameter('collection_date'));
    $this->qubitRequest->setPatronNotes($request->getParameter('notes'));
    $this->qubitRequest->setItemTitle($this->resource->getTitle());
		$this->qubitRequest->setItemDate($this->event->getDate());
		$this->qubitRequest->setItemCreator($this->creator->getAuthorizedFormOfName());
    $this->qubitRequest->setCollectionTitle($this->resource->getCollectionRoot()->getTitle());
	  $this->qubitRequest->save();
    nlwCirculationPlugin::printRequest($this->qubitRequest->getId());
  }
}

