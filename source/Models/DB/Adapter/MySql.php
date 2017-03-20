<?php

namespace Generator\Models\DB\Adapter;

class MySql extends Adapter {

    /**
     * Holds PDO instance
     * 
     * @var \PDO
     */
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Return all the tables of the database
     *   
     * @return array
     */
    public function listAllTable() {
        $output = array();
        $details = $this->pdo
                    ->query('SHOW TABLES')
                    ->fetchAll();

        foreach ($details as $record) {
            $output[] = $record[0];
        }

        return $output;
    }

    /**
     * Generate Table details.
     * 
     * @param string $table
     * @return array
     * Format:
     *  [
     *      <fieldName:string> => [
     *          <name:string>,
     *          <key:string>
     *          <datatype:string>
     *          <nullable:boolean>
     *          <default:mixed>
     *      ],
     *      ...
     *  ]
     */
    public function getTableDetails($table) {
        $output = array();
        $details = $this->pdo
                    ->query("DESCRIBE {$table}")
                    ->fetchAll();

        foreach ($details as $record) {
            $output[$record['Field']]['name'] = $record['Field'];
            $output[$record['Field']]['key'] = $record['Key'];
            $output[$record['Field']]['datatype'] = $record['Type'];
            $output[$record['Field']]['nullable'] = ($record['Null'] === 'NO') ? false : true;
            $output[$record['Field']]['default'] = $record['Default'];
        }

        return $output;
    }

}