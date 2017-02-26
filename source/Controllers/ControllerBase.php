<?php

namespace Generator\Controllers;

class ControllerBase {
    
    /**
     * The Request object passed to each matched route
     *
     * @type Request
     */
    protected $request;

    /**
     * The Response object passed to each matched route
     *
     * @type AbstractResponse
     */
    protected $response;

    /**
     * The service provider object passed to each matched route
     *
     * @type ServiceProvider
     */
    protected $service;

    /**
     * A generic variable passed to each matched route
     *
     * @type mixed
     */
    protected $app;
    
    /**
    *  Klien instance
    *
    * @type Klein
    */
    protected $klein;
    
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
        $this->klein = isset($options['klein']) ? $options['klein'] : null;
        $this->request = isset($options['request']) ? $options['request'] : null;
        $this->response = isset($options['response']) ? $options['response'] : null;
        $this->service = isset($options['service']) ? $options['service'] : null;
        $this->app = isset($options['app']) ? $options['app'] : null;
    }
}