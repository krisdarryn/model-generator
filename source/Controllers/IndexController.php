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
    * Note: rendering of view must always be the last code to call.
    */
    public function index() {
        $this->overrideRegisterCustomJS(array(
            'indexPageJs' => JS_PATH . 'index/index.js'
        ));

        $this->service
             ->render(VIEW_PATH . 'index/index.phtml');
    }

    /**
     * Override parent setHTMLVar() to append additional variables
     *
     * @return void
     */
    protected function setHTMLVar() {
        parent::setHTMLVar();

        $this->htmlVar['htmlTitleSuffix'] = 'Landing';
    }
}