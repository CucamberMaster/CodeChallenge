<?php
namespace Repository\admin;

use PDO;

abstract class AdminManager implements AdminInterface
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}
