<?php
namespace Access\Utils;

class ControllerParser
{
	protected function _getControllerAction($moduleDir, $moduleName)
	{
		if (!in_array($moduleName, $this->excludedModules))
		{
			$moduleResource = array();
			$controllers = glob(join(DIRECTORY_SEPARATOR, array($moduleDir, $moduleName, '[!\.]*')));
			foreach($controllers as $controller)
			{
				$controllerName = $this->_getBaseName($controller);
				$actionPath = join(DIRECTORY_SEPARATOR,
					array($moduleDir, $moduleName, $controllerName, '[!\.]*'));
				$moduleResource[$controllerName] = array_map(array($this, '_getBaseName'), glob($actionPath));
			}
			return $moduleResource;
		}
	}

	protected function _getBaseName($name)
	{
		$fName = end(explode(DIRECTORY_SEPARATOR, $name));
		return explode('.', $fName)[0];
	}

	public static function getModulesLoaded(array $loadedModules)
	{
		foreach($loadedModules as $module)
		{
			$config = $module->getConfig();
			$view_config = $config['view_manager']['template_path_stack'];
			$result = array_map(array($this, '_getControllerAction'), $view_config);
		}
	}
}