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
		$this->event = $this->resource->getCreationEvents()->__get(0);
		/* 
    $criteria = new Criteria;
    $criteria->addJoin(QubitActor::ID, QubitEvent::ACTOR_ID);
    $criteria->add(QubitEvent::OBJECT_ID, $this->id);

    if (isset($options['eventTypeId'])) {
      $criteria->add(QubitEvent::TYPE_ID, $options['eventTypeId']);
    }

    if (isset($options['cultureFallback']) && true === $options['cultureFallback']) {
      $criteria->addAscendingOrderByColumn('authorized_form_of_name');
      $criteria = QubitCultureFallback::addFallbackCriteria($criteria, 'QubitActor', $options);
    }
    $this->actors = QubitActor::get($criteria);
		*/

		$this->qubitRequest = new QubitRequest();
    $this->qubitRequest->setObjectId($this->resource->id);
    $this->qubitRequest->setRequestTypeId('1');
    $this->qubitRequest->setStatus('1');
    $this->qubitRequest->setPhysicalObject($request->getParameter('physical_object'));
    $this->qubitRequest->setExpiryDate(date("Y-m-d",strtotime("+1 week")));
    $this->qubitRequest->setPatronBarcode($user->getAttribute('employeeNumber'));
    $this->qubitRequest->setPatronType($user->getAttribute('employeeType'));
    $this->qubitRequest->setPatronName($user->getAttribute('employeeName'));
    $this->qubitRequest->setCollectionDate($request->getParameter('collection_date'));
    $this->qubitRequest->setPatronNotes($request->getParameter('notes'));
    $this->qubitRequest->setItemTitle($this->resource->getTitle());
		$this->qubitRequest->setItemDate($this->event->getDate());
		$this->qubitRequest->setItemCreator($this->creator->getAuthorizedFormOfName());
    $this->qubitRequest->setCollectionTitle($this->resource->getCollectionRoot()->getTitle());
	  $this->qubitRequest->save();
    //$this->redirect(array($resource, 'module' => 'informationobject', 'slug' => $slug));
  }
  
  
  /*
  public function executeUpdate($request) {
    
    $this->qubitRequest = new QubitRequest();
    $this->qubitRequest->set
  }
  */
  
}
