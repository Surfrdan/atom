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
    $user = $this->getUser();
    $path = $request->getPathInfo();
    $pieces = explode("/", $path, 3);
    $this->slug = $pieces[1];
    
    $criteria = new Criteria;
    $criteria->add(QubitObject::ID, $request->getParameter('id'));
    $this->resource = QubitObject::get($criteria)->__get(0);
    
    
  }
  
  
  /*
  public function executeUpdate($request) {
    
    $this->qubitRequest = new QubitRequest();
    $this->qubitRequest->set
  }
  */
  
}