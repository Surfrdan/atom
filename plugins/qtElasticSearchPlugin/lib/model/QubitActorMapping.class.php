<?php

/*
* This file is part of Qubit Toolkit.
*
* Qubit Toolkit is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Qubit Toolkit is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Qubit Toolkit.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * This class is used to provide a model mapping for storing QubitActor objects
 * within an ElasticSearch document index.
 *
 * @package    qtElasticSearchPlugin
 * @author     MJ Suhonos <mj@artefactual.com>
 * @version    svn:$Id: QubitActorMapping.class.php 10316 2011-11-14 22:40:18Z mj $
 */
class QubitActorMapping extends QubitMapping
{
  static function getProperties()
  {
    return array(
      'slug' => array(
        'type' => 'string',
        'index' => 'not_analyzed'),
      'entityTypeId' => array(
        'type' => 'integer',
        'index' => 'not_analyzed',
        'include_in_all' => false))
      + self::getI18nProperties()
      + self::getTimestampProperties();
  }
}
