<?php
namespace Access\Model;

interface ResourceManagerInterface
{
	public function getModules();
	public function getControllers($moduleId);
	public function getActions($moduleId, $controllerId);
}