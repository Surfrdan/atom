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
class nlwCirculationPluginRequestAction extends sfAction
{
  
  public function execute($request)
  {
    $user = $this->getUser();
    $path = $request->getPathInfo();
    $pieces = explode("/", $path, 3);
    $this->slug = $request->getParameter('slug');
    
    
    $criteria = new Criteria;
    $criteria->add(QubitSlug::SLUG, $this->slug);
    $criteria->addJoin(QubitSlug::OBJECT_ID, QubitObject::ID);
    $this->resource = QubitObject::get($criteria)->__get(0);

    $criteria = new Criteria;
    $criteria->setDistinct();
    $criteria->add(QubitRelation::TYPE_ID, QubitTerm::HAS_PHYSICAL_OBJECT_ID);
    $criteria->addJoin(QubitRelation::OBJECT_ID, QubitInformationObject::ID);
    $criteria->addJoin(QubitRelation::SUBJECT_ID, QubitPhysicalObject::ID);
    $this->physicalObjects = QubitPhysicalObject::get($criteria);

		$criteria = new Criteria;
    $criteria->add(QubitTerm::TAXONOMY_ID, QubitTaxonomy::LEVEL_OF_DESCRIPTION_ID);
    $criteria->addJoin(QubitTerm::ID, QubitTermI18n::ID);
    $criteria->add(QubitTermI18n::CULTURE, sfContext::getInstance()->user->getCulture());

    $term = QubitTermI18n::getOne($criteria);
    $this->levelOfDescription = $term->name;
	 		
		$pathArray = $request->getPathInfoArray();
		if ($pathArray['employeeNumber']) {
			$user->setAttribute('employeeNumber', $pathArray['employeeNumber']);
		}
		if ($pathArray['employeeType']) {
			$user->setAttribute('employeeType', $pathArray['employeeType']);
		}   
		if ($pathArray['givenName']) {
			$user->setAttribute('employeeName', $pathArray['givenName'] . ' ' .$pathArray['sn']);
		}
		
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
    
  }
  
}
