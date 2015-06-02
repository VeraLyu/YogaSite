<?php
namespace Access\Model;

use ResourceManagerInterface;

use Access\Utils\ControllerParser;

class ResourceManager implements ResourceManagerInterface
{
	protected $resCache;
	protected $modules;

	public function __construct($cache, $modules)
	{
		$this->resCache = $cache;
		$this->modules = $modules;
	}

	protected function _getItemFromCache($itemName)
	{
		//retrieve all controllers from cache
		if (!$this->resCache->hasItem($itemName))
		{
			$this->_initCache();
		}
		//if this item does not exist, return null
		return $this->resCache->getItem($itemName);
	}

	public function getModules()
	{
		return $this->_getItemFromCache('modules');
	}

	public function getControllers($moduleId)
	{
		return $this->_getItemFromCache('modules-'.$moduleId);
	}

	public function getActions($moduleId, $controllerId)
	{
		return $this->_getItemFromCache(
				'modules-'.$moduleId.'-controllers-'.$controllerId);
	}

	protected function _initCache()
	{
		// flush staled data
		$this->resCache->flush();
		$config = ControllerParser::getModulesLoaded($this->modules);
		$this->resCache->setItem('modules', array_keys($config));
		foreach($config as $moduleName=>$moduleConfig)
		{
			$this->resCache->setItem('modules-'.$moduleName, array_keys($moduleConfig));
			foreach($moduleConfig as $controller=>$actions)
				$this->resCache->setItem(
						'modules-'.$moduleName.'-controllers-'.$controller,
						$actions);
		}
	}
}