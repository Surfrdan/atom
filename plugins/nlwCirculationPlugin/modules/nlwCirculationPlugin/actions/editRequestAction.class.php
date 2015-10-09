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
class nlwCirculationPluginEditRequestAction extends sfAction
{
  public function execute($request)
  {
    $user = $this->getUser();
    $path = $request->getPathInfo();

    $staff = false;
    
    if($user->hasGroup(99)) {
      $this->staff = true;
      if($request->getParameter('request_id')) {
        $requestId = $request->getParameter('request_id');
        $requestCriteria = new Criteria;
        $requestCriteria->add(QubitRequest::ID, $requestId);
        $this->qubitRequest = QubitRequest::get($requestCriteria)->__get(0);
        
        $archiveCriteria = new Criteria;
        $archiveCriteria->add(QubitObject::ID, $this->qubitRequest->getObjectId());
        $slug = $this->resource->slug;
      }
    } else {
      $slug = $request->getParameter('slug');
      $archiveCriteria = new Criteria;
      $archiveCriteria->add(QubitSlug::SLUG, $slug);
      $archiveCriteria->addJoin(QubitSlug::OBJECT_ID, QubitObject::ID);
    }
    
    $this->statuses = QubitRequestStatus::getAll();
    $this->resource = QubitInformationObject::get($archiveCriteria)->__get(0);
    
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
  }
    
}
