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

    public function selectData($sql, $values)
    {
        $db = $this->getDatabaseConnection();

        $stmt = $db->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
