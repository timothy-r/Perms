<?php namespace Ace\Perm;

use Ace\Perm\Perm;

/**
* @todo update to use the Perm class throughout
*/
interface StoreInterface
{
    /**
    * get all perm names for this Subject Object pair
    * @return Ace\Perm\Perm
    */
    public function get($subject, $object);
    
    /**
    * Get all Perms for this Subject
    * @return array of Ace\Perm\Perm instances
    */
    public function getForSubject($subject);

    /**
    * Stores Perm's state
    */
    public function update(Perm $perm);

    /**
    * Removes perm from the Subject Object pair
    * @todo this should remove all perm values for the Perm instance
    */
    public function remove(Perm $perm);
}
