<?php

namespace Generator\Controllers;

class PageNotFoundController extends ControllerBase {
    
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
    
    /**
    * Index page
    */
    public function index() {
        return 'Page not found';
    }
}