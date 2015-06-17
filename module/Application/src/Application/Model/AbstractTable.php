<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;


class AbstractTable
{
    protected $tableGateway;
    protected $idColumn;

    public function __construct(TableGateway $tableGateway, $idColumn)
    {
        $this->tableGateway = $tableGateway;
        $this->idColumn = $idColumn;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getObject($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array($this->idColumn => $id));
        $cnt = $rowset->count();

        if (!$cnt) {
            throw new \Exception("Could not find row $id");
        }
        return $rowset;
    }

    public function saveObject($object)
    {
        $data = $object->getArrayCopy();
        unset($data[$this->idColumn]);
        $id = $object->{$this->idColumn};
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }
        else {
            try {
                $this->getObject($id);
                $this->tableGateway->update($data, array($this->idColumn=>$id));
            }
            catch (Exception $e) {
                throw new \Exception("Unable to update".$e->getMessage());
            }
        }
    }

    public function deleteObject($id) {
        $this->tableGateway->delete(array($this->idColumn =>(int) $id));
    }
}
