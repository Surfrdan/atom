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

class nlwCirculationPluginConfiguration extends sfPluginConfiguration
{
  public static
    $summary = 'National Library of Wales Circulation Plugin',
    $version = '1.0.0';

  public function contextLoadFactories(sfEvent $event)
  {
    sfContext::getInstance()->response->addStylesheet('/plugins/nlwCirculationPlugin/css/request.css');
  }

  public function setup() // loads handler if needed
  {
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
      $configCache = $this->configuration->getConfigCache();
      $configCache->registerConfigHandler('config/circulation.yml', 'sfDefineEnvironmentConfigHandler',
        array('prefix' => 'circulation_'));
      $configCache->checkConfig('config/circulation.yml');
    }
  }    

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
      $configCache = $this->configuration->getConfigCache();
      include($configCache->checkConfig('config/circulation.yml'));
    }
    $this->dispatcher->connect('context.load_factories', array($this, 'contextLoadFactories'));

    $decoratorDirs = sfConfig::get('sf_decorator_dirs');
    $decoratorDirs[] = $this->rootDir.'/templates';
    sfConfig::set('sf_decorator_dirs', $decoratorDirs);
      
    $enabledModules = sfConfig::get('sf_enabled_modules');
    $enabledModules[] = 'nlwCirculationPlugin';
    sfConfig::set('sf_enabled_modules', $enabledModules);
  }
}
