<?php

namespace Generator\Models;


class ModelBase {

    /**
     * Holds the global $klein
     * 
     * @var \Klein\Klein
     */
    protected $klein;

    public function __construct() {
        $this->klein = $GLOBALS['klein'];
    }

}