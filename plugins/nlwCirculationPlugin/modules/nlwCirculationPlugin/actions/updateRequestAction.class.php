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
class nlwCirculationPluginUpdateRequestAction extends sfAction
{
  
  public function execute($request)
  {
    $criteria = new Criteria;
    $criteria->add(QubitRequest::ID, $request->getParameter('request_id'));
    
    $user = $this->getUser();
    
    $this->qubitRequest = QubitRequest::get($criteria)->__get(0);
    $this->qubitRequest->setStatus($request->getParameter('status'));
    $this->qubitRequest->setExpiryDate($request->getParameter('expiry_date'));
    $this->qubitRequest->setPatronBarcode($request->getParameter('patron_barcode'));
    $this->qubitRequest->setCollectionDate($request->getParameter('collection_date'));
    $this->qubitRequest->setPatronNotes($request->getParameter('patron_notes'));
    $this->qubitRequest->setStaffNotes($request->getParameter('staff_notes'));
    $this->qubitRequest->save();
    $this->redirect(array($resource, 'module' => 'nlwCirculationPlugin', 'action' => 'listRequests'));
  }
  
  
  /*
  public function executeUpdate($request) {
    
    $this->qubitRequest = new QubitRequest();
    $this->qubitRequest->set
  }
  */
  
}