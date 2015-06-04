<?php
namespace Access\Utils;

class ControllerParser
{
	protected static $excludedModules = array('access', 'Application');
	protected static function _getControllerAction($moduleName, $moduleDir)
	{
		if (!in_array($moduleName, self::$excludedModules))
		{
			$moduleResource = array();
			$controllers = glob(join(DIRECTORY_SEPARATOR, array($moduleDir, $moduleName, '[!\.]*')));
			foreach($controllers as $controller)
			{
				$controllerName = self::_getBaseName($controller);
				$actionPath = join(DIRECTORY_SEPARATOR,
					array($moduleDir, $moduleName, $controllerName, '[!\.]*'));
				$moduleResource[$controllerName] = array_map(array(get_called_class(), '_getBaseName'), glob($actionPath));
			}
			return array($moduleName=>$moduleResource);
		}
		return array();
	}

	protected static function _getBaseName($name)
	{
		$result = explode(DIRECTORY_SEPARATOR, $name);
		$fName = end($result);
		return explode('.', $fName)[0];
	}

	public static function getModulesLoaded(array $loadedModules)
	{
		$moduleDic = array();
		foreach($loadedModules as $module)
		{
			$config = $module->getConfig();
			$view_config = $config['view_manager']['template_path_stack'];
			$result = array_map(
					array(get_called_class(), '_getControllerAction'),
					array_keys($view_config),
					$view_config)[0];
			$moduleDic = array_merge($moduleDic, $result);
		}
		return $moduleDic;
	}
}