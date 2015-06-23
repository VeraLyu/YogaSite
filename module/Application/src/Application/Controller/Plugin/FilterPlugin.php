<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FilterPlugin extends AbstractPlugin
{
	public function filterResultSet($resultSet, array $filterField)
	{
	    $res = $resultSet->toArray();
	    foreach ($res as &$row)
	    {
	        foreach ($filterField as $key)
	            unset($row[$key]);
	    }
	    return $res;
	}
}