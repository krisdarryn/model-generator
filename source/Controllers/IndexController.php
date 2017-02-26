<?php

namespace Generator\Controllers;

class IndexController extends ControllerBase {
    
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
        $this->service
             ->render(VIEW_PATH . 'index/index.phtml', array(
                'htmlTitleSuffix' => 'Landing'
             ));
    }
}