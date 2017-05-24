<?php

namespace App;

class DataSource
{
    /**
     * @var \PDO $databaseConnection;
     */
    private $databaseConnection;

    public function loadFixtures()
    {
        $this->createDatabaseConnection();
        $db = $this->getDatabaseConnection();
        $sql = file_get_contents('database.sql');
        $db->exec($sql);
    }

    protected function getDatabaseConnection()
    {
        return $this->databaseConnection;
    }

    private function createDatabaseConnection()
    {
        $this->databaseConnection = new \PDO('sqlite:memory');
        $this->databaseConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
