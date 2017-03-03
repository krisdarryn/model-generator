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
	
	/**
	 * Home Page
	 */
	public function index() {
		$tables = $this->schemaUtility->listAllTables();

		debug_r(\Generator\Models\ClassGenerator::generate(array(
			'namespace' => 'Generator\Models',
			'uses' => array(
				'Generator\Models\Class1',
				'Generator\Models\Class2',
				'Generator\Models\Class3'
			),
			'parent' => 'General',
			'className' => 'Properties', 
			'fields' => $this->schemaUtility->getTableDetails('properties')
		)));
exit;
		$this->service
			 ->render(VIEW_PATH . 'home/index.phtml', array(
					'tables' => $tables
				));
	}
}