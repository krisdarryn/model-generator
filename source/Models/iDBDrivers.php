<?php

namespace Generator\Models;

interface iDBDrivers {
 
    /**
     * MySQL driver
     *
     * @var string
     */
    const MYSQL = 'mysql';

    /**
     * Postgres driver
     * 
     * @var string
     */
    const POSTGRES = 'postgres';

    /**
     * SQL Server driver
     * 
     * @var string
     */
    const SQLSERVER = 'sqlserver';

    /**
     * SQLite driver
     * 
     * @var string
     */
    const SQLITE = 'sqlite';
}