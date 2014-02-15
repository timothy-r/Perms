<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;
use Doctrine\DBAL\Connection;

class Store implements StoreInterface
{
    private $db;
    
    private $table = 'perm';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get($subject, $object)
    {
        $sql = "SELECT * FROM perm WHERE subject = ? AND object = ?";
        $options = [$subject, $object];
        $results = $this->db->fetchAll($sql, $options);
        if (!count($results)){
            throw new NotFoundException;
        }
        $perms = [];
        foreach($results as $result){
            // remove any duplicates
            $perms [$result['value']]= $result['value'];
        }
        $perm = new Perm($subject, $object, $perms);
        return $perm;
    }

    public function getForSubject($subject)
    {
        return [];
    }

    public function update(Perm $perm)
    {
        $types = ['text', 'text', 'text'];
        foreach ($perm->added() as $value){
            $options = ['subject' => $perm->getSubject(), 'object' => $perm->getObject(), 'value' => $value];
            $result = $this->db->insert($this->table, $options, $types);
        }

        foreach ($perm->removed() as $value){
            $options = ['subject' => $perm->getSubject(), 'object' => $perm->getObject(), 'value' => $value];
            $result = $this->db->delete($this->table, $options);
        }
        return true;
    }

    public function remove(Perm $perm)
    {
        $options = ['subject' => $perm->getSubject(), 'object' => $perm->getObject()];
        $result = $this->db->delete($this->table, $options);
        // @todo test result
        return true;
    }
}
