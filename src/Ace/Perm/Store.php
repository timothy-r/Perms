<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;
use Doctrine\DBAL\Connection;

class Store implements StoreInterface
{
    private $db;

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

    public function add($subject, $object, $value)
    {
        $table = 'perm';
        $options = ['subject' => $subject, 'object' => $object, 'value' => $value];
        $types = ['text', 'text', 'text', 'text', 'text'];
        $result = $this->db->insert($table, $options, $types);
        return true;
    }

    public function remove(Perm $perm)
    {
        $table = 'perm';
        $subject = $perm->getSubject();
        $object = $perm->getObject();
        $options = ['subject' => $subject, 'object' => $object];
        $result = $this->db->delete($table, $options);
        // @todo test result
        return true;
    }
}
