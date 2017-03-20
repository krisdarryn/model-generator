<?php

namespace Generator\Models\DB\Adapter;

abstract class Adapter {

    /**
     * Return all the tables of the database
     *
     * @return array
     */
    public abstract function listAllTable();

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
    public abstract function getTableDetails($table);
}