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
class nlwCirculationPluginListRequestsAction extends sfAction
{
  public function execute($request)
  {
		sfContext::getInstance()->response->addStylesheet('/plugins/nlwCirculationPlugin/css/blue/style.css');
    sfContext::getInstance()->response->addJavaScript('/plugins/nlwCirculationPlugin/js/jquery.tablesorter.js');
    sfContext::getInstance()->response->addJavaScript('/plugins/nlwCirculationPlugin/js/request.table.js');

    $user = $this->getUser();
		if (!$user->isAuthenticated()) {
      QubitAcl::forwardUnauthorized();
    }

		if ($request->getGetParameter('expired')) {
			$this->expired = $request->getGetParameter('expired');
		} else {
			$this->expired = array(false);
		}
		if ($request->getGetParameter('request_statuses')) {
			$this->request_statuses = $request->getGetParameter('request_statuses');
		} else {
			$deliberate = $request->getGetParameter('deliberate');
			if (!$deliberate) {
				$this->request_statuses = array(1,2,3);
			}
		}

   	$criteria = new Criteria;
		foreach ($this->request_statuses as $request_status) {
    	$criteria->addOr(QubitRequest::STATUS, $request_status);
		}
		if (!isset($this->request_statuses)) {
			$criteria->add(QubitRequest::STATUS, Criteria::ISNULL);
		}

		if ($this->expired[0] == 'true') {
			$criteria->addOr(QubitRequest::EXPIRY_DATE, date("Y-m-d H:i:s"), Criteria::LESS_THAN);
		} else {
			$criteria->add(QubitRequest::EXPIRY_DATE, date("Y-m-d H:i:s"), Criteria::GREATER_THAN);
		}

		$this->statuses = array();
		foreach (QubitRequestStatus::getAll() as $s) {
			$this->statuses[$s->getId()] = $s->getStatus(); 
		}

    $path = $request->getPathInfo();
			
    $this->pager = new QubitPager('QubitRequest');
    $this->pager->setCriteria($criteria);
    $this->pager->setMaxPerPage(100);
    $this->pager->setPage($request->page);
		
		foreach ($request->getGetParameters() as $param => $value) {
			$this->pager->setParameter($param, $value);
		}

		if ($request->getGetParameter('request_statuses')) {
			$this->request_statuses = $request->getGetParameter('request_statuses');
		} else {
			$deliberate = $request->getGetParameter('deliberate');
			if (!$deliberate) {
				$this->request_statuses = array(1,2,3);
			}
		}
		$this->qubitRequests = $this->pager->getResults();
  }
  
}
