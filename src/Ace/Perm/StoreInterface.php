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
    * Adds this perm name to the Subject Object pair
    */
    public function add($subject, $object, $value);

    /**
    * Stores Perm's state
    */
    public function update(Perm $perm);

    /**
    * Removes perm from the Subject Object pair
    * @todo this should remove all perm values for the Perm instance
    */
    public function remove(Perm $perm);
    
    /**
    * Updates the perm's values, both remove and add new ones
    */
    // public function update(Perms $perm); 
}
