<?php

namespace Generator\Models;

use Generator\Models\DB\iDBDrivers;
use Generator\Models\DB\DBConnection;
use Generator\Models\DB\Adapter\MySql;

class SchemaUtility extends ModelBase {
    
    /**
     * Holds DBConnection instance
     * 
     * @var \Generator\Models\DBConnection
     */
    private $dbInstance;

    /**
     * Holds PDO instance
     * 
     * @var \PDO
     */
    private $pdo;


    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->dbInstance = DBConnection::getInstance($this->klein->service()->sharedData()->get('session')->get('dbCredentials'));
        $this->pdo = $this->dbInstance->getConnectionInstance()->getPdo();
    }

    /**
     * Create appropriate DB Adapter
     *
     * @return \Generator\Models\DB\Adapter
     */
    public function getDBAdapter() {
        
        switch ($this->klein->service()->sharedData()->get('session')->get('dbCredentials')['driver']) {
            case iDBDrivers::MYSQL:
                return new Mysql($this->pdo);
        }

    }

}