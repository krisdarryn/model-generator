<?php

namespace Generator\Models\DB;

use Illuminate\Database\Capsule\Manager;

class DBConnection {
    
    /**
     * Default connection name
     * 
     * @var string
     */
    const CONNECTION_NAME = 'model-gen';

    /**
     * Singleton instance
     * 
     * @var null|\Generator\Models\DBCOnnection
     */
    public static $instance = null;

    /**
     * Holds the instance of Database manager
     * 
     * @var \Illuminate\Database\Capsule\Manager
     */
    private $manager;

    /**
     * Constuctor
     * 
     * @param array $options
     */
    private function __construct($options) {
        $this->manager = new Manager();

        $this->manager->addConnection(array(
            'driver' => $options['driver'],
            'host' => $options['host'],
            'database' => $options['database'],
            'username' => $options['username'],
            'password' => $options['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ), self::CONNECTION_NAME);

        $this->manager->setAsGlobal();
    }

    /**
     * Creates a singleton instance
     * 
     * @param array $credentials
     * @return \Generator\Models\DBConnection
     */
    public static function getInstance($credentials) {

        if (self::$instance === null) {
            self::$instance = new self($credentials);
        }

        return self::$instance;
    }

    /**
     * Get the manager instance
     * 
     * @return \Illuminate\Database\Capsule\Manager
     */
    public function getManager() {
        return $this->manager;
    }

    /**
     * Get the connection instance to be used to do database queries
     * 
     * @return  \Illuminate\Database\Connection|boolean
     */
    public function getConnectionInstance() {
        return $this->manager->connection(self::CONNECTION_NAME);
    }

    /**
     * Check if it succesfully connected to the database
     * 
     * @return boolean
     */
    public function isConnected() {
        $isConnected = true;

        try {

            if ($this->manager) {
                $this->manager->getConnection(self::CONNECTION_NAME)->getPdo();    
            } else {
                $isConnected = false;
            }
            
        } catch (\Exception $e) {
            return false;
        }

        return $isConnected;
    }
 
}