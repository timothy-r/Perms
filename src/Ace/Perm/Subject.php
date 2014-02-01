<?php namespace Ace\Perm;

class Subject implements SubjectInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
    }
}
