<?php

namespace TestBlog\Kernel;

use Doctrine\ORM\EntityManagerInterface;

class Db
{
    
    private $dbConfig;
    public $connection;
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    public function __construct($connection, $entityManager)
    {
        $this->connection = $connection;
        $this->entityManager = $entityManager;
    }

}
