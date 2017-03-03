<?php

namespace Generator\Models;

class SchemaUtility {
    
    /**
     * Holds DBConnection instance
     * 
     * @var \Generator\Models\DBConnection
     */
    private $dbInstance;

    /**
     * Holds service provider instance
     * 
     * @var \Klein\ServiceProvider
     */
    private $service;

    /**
     * Holds PDO instance
     * 
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructor
     * 
     * @param \Klein\ServiceProvider $service
     */
    public function __construct($service) {
        $this->service = $service;
        $this->dbInstance = DBConnection::getInstance($this->service->sharedData()->get('session')->get('dbCredentials'));
        $this->pdo = $this->dbInstance->getConnectionInstance()->getPdo();
    }

    /**
     * List all talbes inside the schema
     * 
     * @return array
     */
    public function listAllTables() {
        return $this->pdo
                    ->query('SHOW TABLES')
                    ->fetchAll();
    }

    /**
     * Fetch details of the specified table
     * 
     * @param  string $tableName
     * @return array
     */
    public function getTableDetails($tableName) {
        return $this->pdo
                    ->query("DESCRIBE {$tableName}")
                    ->fetchAll();
    }
}