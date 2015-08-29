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

/**
 * Import csv data
 *
 * @package    symfony
 * @subpackage task
 * @author     Mike Cantelon <mike@artefactual.com>
 */
abstract class csvImportBaseTask extends arBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('filename', sfCommandArgument::REQUIRED, 'The input file (csv format).')
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'qubit'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'cli'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('rows-until-update', null, sfCommandOption::PARAMETER_OPTIONAL, 'Output total rows imported every n rows.'),
      new sfCommandOption('skip-rows', null, sfCommandOption::PARAMETER_OPTIONAL, 'Skip n rows before importing.'),
      new sfCommandOption('error-log', null, sfCommandOption::PARAMETER_OPTIONAL, 'File to log errors to.')
    ));
  }

  /**
   * Validate import-related options, throwing exceptions or warning when
   * appropriate
   *
   * @param array $options  options
   *
   * @return void
   */
  protected function validateOptions($options)
  {
    $numericOptions = array('rows-until-update', 'skip-rows');

    foreach($numericOptions as $option)
    {
      if ($options[$option] && !is_numeric($options[$option]))
      {
        throw new sfException($option .' must be an integer');
      }
    }

    if ($options['error-log'] && !is_dir(dirname($options['error-log'])))
    {
      throw new sfException('Path to error log is invalid.');
    }

    if ($this->acceptsOption('source-name') && !$options['source-name'])
    {
      print "WARNING: If you're importing multiple CSV files as part of the "
        ."same import it's advisable to use the source-name CLI option to "
        ."specify a source name (otherwise the filename will be used as a "
        . "source name).\n";
    }
  }

  /**
   * Checks to see if a particular option is supported
   *
   * @param string $name  option name
   *
   * @return boolean
   */
  protected function acceptsOption($name)
  {
    foreach($this->getOptions() as $option)
    {
      if ($name == $option->getName()) return true;
    }
    return false;
  }

  /**
   * Import events
   */
  static function importEvents(&$import)
  {
    // Add ad-hoc events
    if (isset($import->rowStatusVars['eventActors']))
    {
      foreach($import->rowStatusVars['eventActors'] as $index => $actor)
      {
        // Initialize data that'll be used to create the event
        $eventData = array(
          'actorName' => ($actor != 'NULL') ? $actor : null
        );

        // Define whether each event-related column's values go directly
        // into an event property or put into a variable for further
        // processing
        $eventColumns = array(
          'eventTypes' => array(
            'variable'      => 'eventType',
            'requiredError' => 'You have not populated the eventTypes column.'
          ),
          'eventPlaces'         => array('variable' => 'place'),
          'eventDates'          => array('property' => 'date'),
          'eventStartDates'     => array('property' => 'startDate'),
          'eventEndDates'       => array('property' => 'endDate'),
          'eventDescriptions'   => array('property' => 'description'),
          'eventActorHistories' => array('property' => 'actorHistory')
        );

        // Handle each of the event-related columns
        $eventType = false;
        $place     = false;
        foreach($eventColumns as $column => $definition)
        {
          if (!isset($import->rowStatusVars[$column]) && isset($definition['requiredError']))
          {
            throw new sfException('You have populated the eventActors column but not the eventTypes column.');
          }

          // Ignore 'NULL' values
          if (!isset($import->rowStatusVars[$column][$index]) || $import->rowStatusVars[$column][$index] == 'NULL')
          {
            continue;
          }

          $value = $import->rowStatusVars[$column][$index];

          // Allow column value(s) to set event property
          if (isset($definition['property']))
          {
            $eventData[($definition['property'])] = $value;
          }

          // Allow column values(s) to set variable
          if (isset($definition['variable']))
          {
            $$definition['variable'] = $value;
          }
        }

        // If an event type has been specified, attempt to create the event
        if ($eventType)
        {
          // Do lookup of type ID
          $typeTerm = $import->createOrFetchTerm(QubitTaxonomy::EVENT_TYPE_ID, $eventType);
          $eventTypeId = $typeTerm->id;

          // Create event
          $event = $import->createOrUpdateEvent($eventTypeId, $eventData);

          // Create a place term if specified
          if ($place)
          {
            // Create place
            $placeTerm = $import->createTerm(QubitTaxonomy::PLACE_ID, $place);
            $import->createObjectTermRelation($event->id, $placeTerm->id);
          }
        }
        else
        {
          throw new sfException('eventTypes column need to be populated.');
        }
      }
    }
  }

  /**
   * Import creation events
   */
  static function importCreationEvents(&$import)
  {
    $creationEvents = array();

    // Get creators and creator histories
    if (isset($import->rowStatusVars['creators']) && count($import->rowStatusVars['creators']))
    {
      foreach ($import->rowStatusVars['creators'] as $index => $creator)
      {
        // Init eventData array and add creator name. Ignore fields with value: 'NULL'.
        $eventData = array();

        if ($creator !== 'NULL')
        {
          $eventData['actorName'] = $creator;
        }

        // Add creator history if specified
        if (isset($import->rowStatusVars['creatorHistories'][$index]) &&
            $import->rowStatusVars['creatorHistories'][$index] !== 'NULL')
        {
          $eventData['actorHistory'] = $import->rowStatusVars['creatorHistories'][$index];
        }

        if (count($eventData))
        {
          array_push($creationEvents, $eventData);
        }
      }
    }

    // Get creation dates
    foreach (array(
      '2.1' => array(
        'date' => 'creatorDates',
        'startDate' => 'creatorDatesStart',
        'endDate' => 'creatorDatesEnd',
        'description' => 'creatorDateNotes'),
      '2.2' => array(
        'date' => 'creationDates',
        'startDate' => 'creationDatesStart',
        'endDate' => 'creationDatesEnd',
        'description' => 'creationDateNotes')) as $version => $columns)
    {
      // Get event data if one of the four columns is populated (grouped by version)
      $index = 0;
      while (isset($import->rowStatusVars[$columns['date']][$index])
        || isset($import->rowStatusVars[$columns['startDate']][$index])
        || isset($import->rowStatusVars[$columns['endDate']][$index])
        || isset($import->rowStatusVars[$columns['description']][$index]))
      {
        $eventData = array();
        foreach ($columns as $property => $column)
        {
          if (isset($import->rowStatusVars[$column][$index])
            && $import->rowStatusVars[$column][$index] != 'NULL')
          {
            $eventData[$property] = $import->rowStatusVars[$column][$index];
          }
        }

        if (count($eventData))
        {
          array_push($creationEvents, $eventData);
        }

        $index++;
      }
    }

    // Create events, if any
    if (count($creationEvents))
    {
      foreach ($creationEvents as $eventData)
      {
        $event = $import->createOrUpdateEvent(QubitTerm::CREATION_ID, $eventData);
      }
    }
  }
}
