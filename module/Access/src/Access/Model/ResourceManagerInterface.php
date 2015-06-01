<?php
namespace Access\Model;

interface ResourceManagerInterface
{
	public function getResources();
	public function getResource(integer $id);
}