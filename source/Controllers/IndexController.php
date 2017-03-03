<?php

namespace Generator\Controllers;

use Generator\Models\iDBDrivers;
use Generator\Models\DBConnection;

class IndexController extends ControllerBase {
    
    /**
     * Holds the list of supported databas drivers
     * 
     * @var array
     */
    private $drivers;

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

        $this->drivers = array(
            iDBDrivers::MYSQL       => 'MySQL',
            iDBDrivers::POSTGRES    => 'Postgres',
            iDBDrivers::SQLSERVER   => 'SQL Server',
            iDBDrivers::SQLITE      => 'SQLite',
        );
    }
    
    /**
    * Index page
    * Note: rendering of view must always be the last code to call.
    */
    public function index() {
        $this->overrideRegisterCustomJS(array(
            'indexPageJs' => JS_PATH . 'index/index.js'
        ));

        if ($this->request->method('post')) {
            $postData = array(
                'driver' => $this->request->paramsPost()->get('driver'),
                'host' => $this->request->paramsPost()->get('host'),
                'database' => $this->request->paramsPost()->get('database'),
                'username' => $this->request->paramsPost()->get('username'),
                'password' => $this->request->paramsPost()->get('password'),
            );
            $dbConnection = DBConnection::getInstance($postData);

            // Check if succesfully connected to the database and save the to session, 
            // otherwise redirect back to the landing page
            if ($dbConnection->isConnected()) {
                $this->session->set('dbCredentials', $postData);
                $this->session->set('isConnected', true);

                return $this->response->redirect(HOME_URI);
            }
            
        }

        /*$a = file_get_contents(__FILE__);
        debug_r(json_encode(htmlentities($a)));*/

        $this->service
             ->render(VIEW_PATH . 'index/index.phtml', array(
                    'dbDrivers' => $this->drivers
                ));
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