<?php

namespace Generator\Controllers;

class HomeController extends ControllerBase {
    
    /**
    * Constructor
    *
    * @param array $options Collection of configuration objects 
    *   - 'klein'       => Klein
    *   - 'request'     => Request
    *   - 'response'    => AbstractResponse
    *   - 'service'     => ServiceProvider
    *   - 'app'         => mixed
    */
    public function __construct($options = array()) {
        parent::__construct($options);
    }
    
    public function index() {
        
        return 'home index';
    }
}