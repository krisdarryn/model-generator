<?php

namespace Generator\Controllers;

use Generator\Models\SchemaUtility;

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
     * Holds common HTML variable
     * 
     * @var array
     */
    protected $htmlVar;

    /**
     * Holds all JS libraries
     * 
     * @var array
     */
    protected $jsLibs;

    /**
     * Holds all CSS libraries
     * 
     * @var array
     */
    protected $cssLibs;

    /**
     * Holds all custom/user define CSS
     * 
     * @var array
     */
    protected $cssCustoms;

    /**
     * Holds all customer/user define JS
     * 
     * @var array
     */
    protected $jsCustoms;

    /**
     * Holds backend variables to be converted in to JS variables
     * 
     * @var array
     */
    protected $jsVars;
    
    /**
    * Holds session instance
    *
    * @var \Aura\Session\Segment
    */
    protected $session;

    /**
     * Holds SchemaUtility instance
     * 
     * @var \Generator\Models\SchemaUtility
     */
    protected $schemaUtility;


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
        $this->session = $this->service
                              ->sharedData()
                              ->get('session');
        
        // Set the default view layout
        $this->service
             ->layout(VIEW_PATH . 'common-layout/index.phtml');

        $this->registerComponents();

        // Access Control List
        // If not connected redirect to the landing page
        $ignoreURI = array(
            BASE_URI, 
            INDEX_URI,
            PAGE_NOT_FOUND_URI,
        );

        if ((!$this->session->get('isConnected') && !in_array($this->request->uri(), $ignoreURI)) ) {
            return $this->response->redirect(BASE_URI);
        } else if ($this->session->get('isConnected') && in_array($this->request->uri(), array(BASE_URI, INDEX_URI)) ) {
            return $this->response->redirect(HOME_URI);
        }

        if ($this->session->get('dbCredentials')) {
            $this->schemaUtility = new SchemaUtility();
        }
    }

    /**
     * Encapsulate component registration
     *
     * @return void
     */
    private function registerComponents() {
        $this->setHTMLVar();
        $this->registerJSLibraries();
        $this->registerCSSLibraries();
        $this->registerCustomJS();
        $this->registerCustomCSS();
        $this->convertToJSVariables();

        // Invoke app helper initialization
        $this->registerHelpers();

        // Register global variables for view
        $this->service
             ->sharedData()
             ->set('htmlVar', $this->htmlVar);

        // Register JS libraries
        $this->service
             ->sharedData()
             ->set('jsLibs', $this->jsLibs);

        // Register CSS libraries
        $this->service
             ->sharedData()
             ->set('cssLibs', $this->cssLibs);

        // Register custom JS
        $this->service
             ->sharedData()
             ->set('jsCustoms', $this->jsCustoms);

        // Register custom CSS
        $this->service
             ->sharedData()
             ->set('cssCustoms', $this->cssCustoms);

        // Register variables to be converted in to JS variables
        $this->service
             ->sharedData()
             ->set('jsVars', $this->jsVars);
    }

    /**
     * Assignment global HTML variables for view
     *
     * @return  void
     */
    protected function setHTMLVar() {
        $this->htmlVar = array(
            'htmlTitle' => HTML_TITLE,
            'htmlTitleSuffix' => ''
        );
    }

    /**
     * Register app helpers e.g. the absolute URI
     * 
     * @return void
     */
    protected function registerHelpers() {
        $this->service
             ->sharedData()
             ->set('appHelper', array(
                'absotuleUri' => $this->request->server()->exists('HTTPS') ? "https://{$this->request->server()->get('SERVER_NAME')}/{$this->request->uri()}" : "http://{$this->request->server()->get('SERVER_NAME')}/{$this->request->uri()}",
             ));
    }

    /**
     * Register JS libraries
     * 
     * @return void
     */
    protected function registerJSLibraries() {
        $this->jsLibs = array(
            'jQuery' => BOWER_PATH . 'jQuery/dist/jquery.min.js',
            'bootstrap' => BOWER_PATH . 'bootstrap-sass/assets/javascripts/bootstrap.min.js',
        );
    }

    /**
     * Register CSS Libraries
     * 
     * @return void
     */
    protected function registerCSSLibraries() {
        $this->cssLibs = array(
            'bootstrap' => CSS_PATH . 'bootstrap.css',
        );
    }

    /**
     * Register custom/user define JS 
     * 
     * @return void
     */
    protected function registerCustomJS() {
        $this->jsCustoms = array();
    }

    /**
     * Overried the registratin of custom JS
     * Appending new custom JS file path
     * 
     * @param  array  $customJS
     * @return void
     */
    protected function overrideRegisterCustomJS($customJS = array()) {
        $this->service
             ->sharedData()
             ->set('jsCustoms', array_merge($this->jsCustoms, $customJS));
    }

    /**
     * Register custom/user define CSS
     * @return [type] [description]
     */
    protected function registerCustomCSS() {
        $this->cssCustoms = array(
            'baseCSS' => CSS_PATH . 'screen.css',
        );
    }

    /**
     * Override the registration of custom CSS
     * Appending new custom CSS file path
     *
     * @param  array  $customCSS
     * @return void
     */
    protected function overrideRegisterCustomCSS($customCSS = array()) {
        $this->service
             ->sharedData()
             ->set('cssCustoms', array_merge($this->cssCustoms, $customCSS));
    }

    /**
     * Register backend variables to be converted in to JS variables
     *
     * e.i.: 
     *     - $this->jsVars['var1'] = 99; Can be accessed in JS via `var1`
     *     - $this->jsVars['arr1'] = array('lorem', 'ipsum'); To access the first value use `arr1[0]`
     *     - $this->jsVars['obj'] = array('name' => 'jim', 'age' => 99); To access the name property use `obj.name`
     * 
     * @return void
     */
    protected function convertToJSVariables() {
        $this->jsVars = array(
            'uri' => $this->request->uri(),
        );
    }

    /**
     * Override the registration of JS variables
     * Appending new variables
     *
     * @param array $jsVars
     * @return void
     */
    protected function overrideToJSVarRegistration($jsVars = array()) {
        $this->service
             ->sharedData()
             ->set('jsVars', array_merge($this->jsVars, $jsVars));
    }
}