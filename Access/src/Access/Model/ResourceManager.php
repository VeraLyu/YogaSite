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

	public function getModules()
	{
		//retrieve all controllers from cache
	}

	public function getControllers($moduleId)
	{
		
	}

	public function getActions($moduleId, $controllerId)
	{
		
	}

	protected function _initCache()
	{
		$this->resCache
		$config = ControllerParser()->getModulesLoaded();
		
	}
}