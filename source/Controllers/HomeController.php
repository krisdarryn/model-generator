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

		$c = \Generator\Models\ClassGenerator::generate(array(
			'namespace' => 'Generator\Models',
			'uses' => array(
				'Generator\Models\Class1',
				'Generator\Models\Class2',
				'Generator\Models\Class3'
			),
			'parent' => 'General',
			'className' => 'Properties', 
			'implements' => array(
				'Class1',
				'Class2',
				'class3'
			),
			'constructorParameters' => array(
				'TypeHint1' => '$typeHint1', 
				'TypeHint2' => '$typeHint2', 
				'$parameter1', 
				'$parameter2', 
			),
			'fields' => $this->schemaUtility->getTableDetails('carts')
		));

		debug_r(htmlentities($c));
exit;
		$this->service
			 ->render(VIEW_PATH . 'home/index.phtml', array(
					'tables' => $tables
				));
	}
}