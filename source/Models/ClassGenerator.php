<?php

namespace Generator\Models;

class ClassGenerator {
    
    /**
     * @var int
     */
    const LOWERCASE_SEPARATED_BY_UNDERSCORE = 0;

    /**
     * @var int
     */
    const LOWER_CAMEL_CASE = 1;

    /**
     * Holds the Metadata for the class to generate
     * Format;
     * [
     *      'namespace' => <string>,
     *      'uses' => [
     *          <namespacedClass:string>
     *      ]
     *      'parent' => <string>
     *      'className' => <string>
     *      'implements' => [
     *          <interface:string>
     *      ]
     *      'constructorParameters' [
     *          <typehint:string> => <value:string>
     *      ],
     *      'fields' => <\Generator\Models\SchemaUtility::getTableDetails():array>,
     *      'noOfIndentSpace' => <int>,
     *      ''
     * ]  
     *
     * @var array
     */
    private $options;

    /**
     * ClassGenerator constructor
     */
    public function __construct($options = null) {
        $this->options = $options;
    }

    /**
     * @param $options
     * Format;
     * [
     *      'namespace' => <string>,
     *      'uses' => [
     *          <namespacedClass:string>
     *      ]
     *      'parent' => <string>
     *      'className' => <string>
     *      'implements' => [
     *          <interface:string>
     *      ]
     *      'constructorParameters' [
     *          <typehint:string> => <value:string>
     *      ],
     *      'fields' => <\Generator\Models\SchemaUtility::getTableDetails():array>,
     *      'noOfIndentSpace' => <int>,
     *      'fieldNamingConvention' <int>,
     *      'setters' => <boolean>,
     *      'getters' => <boolean>
     * ]  
     *
     * @return \Generator\Models\ClassGenerator
     */
    public function setMetaData($options) {
        $this->options = $options;

        return $this;
    }   

    /**
    * Generate Indents in space
    *
    * @param int|null $noOfSpaces
    * @return string
    */
    public function genereateIndentSpace($noOfSpaces = null) {
        return str_repeat(' ', ($noOfSpaces ? $noOfSpaces : $this->options['noOfIndentSpace']));
    }

    /**
     * Generates a doc block comment
     * 
     * @param array $annotations
     * Format: 
     * [
     *   'description' => <string>
     *   'variable' => <string>
     *   'params' => [
     *       <type:string> => <variableName:string>
     *   ],
     *   'return' => <string>
     * ]
     *
     * @return string
     */
    public function generateDocBlockComment($annotations = array()) {
        $comment = $this->genereateIndentSpace() . "/**\n";

        // Prepare the comment description
        if (isset($annotations['description']) && $annotations['description']) {
            $breakComment = explode(' ', $annotations['description']);
            $wordsPerLine = 10;
            $concatWord = '';

            foreach ($breakComment as $key => $word) {
                $concatWord .= "{$word} ";

                if (($key !== 0) && ($key % $wordsPerLine    === 0)) {
                    $comment .= $this->genereateIndentSpace() . " * {$concatWord}\n";
                    $concatWord = '';
                }
                
            }

            $comment .= $this->genereateIndentSpace() . " * {$concatWord}\n";
        }

        // Prepare variable annotation
        if (isset($annotations['variable']) && $annotations['variable']) {
            $comment .= $this->genereateIndentSpace() . " *\n";
            $comment .= $this->genereateIndentSpace() . " * @var {$annotations['variable']}\n";
        }

        // Prepare variable annotation
        if (isset($annotations['params']) && is_array($annotations['params'])) {
            $comment .= $this->genereateIndentSpace() . " *\n";

            foreach ($annotations['params'] as $type => $var) {
                
                if (is_numeric($type)) {
                    $comment .= $this->genereateIndentSpace() . " * @param mixed {$var}\n";
                } else {
                    $comment .= $this->genereateIndentSpace() . " * @param {$type} {$var}\n";
                }
                
            }

        }

        // Prepare return annotation
        if (isset($annotations['return']) && $annotations['return']) {
            $comment .= $this->genereateIndentSpace() . " * @return {$annotations['return']}\n";
        }

        return $comment . $this->genereateIndentSpace() . " */\n";
    }

    /**
     * Convert field to the appropriate naming convention
     *
     * @param string $fieldName
     * @return string
     */
    public function getConvetedField($fieldName) {
        
        if (isset($this->options['fieldNamingConvention'])) {

            switch ($this->options['fieldNamingConvention']) {
                case self::LOWERCASE_SEPARATED_BY_UNDERSCORE:
                    $fieldName = strtolower(preg_replace('/[-_ ]/', '_', $fieldName));
                    break;
                

                case self::LOWER_CAMEL_CASE;
                    $fieldName = str_replace(' ', '', lcfirst(ucwords(preg_replace('/[-_ ]/', ' ', $fieldName))));
            }
        }
        
        return $fieldName;
    }

    /**
     * PHP opening tag
     * 
     * @return string
     */
    public function openTag() {
        return "<?php\n\n";
    }

    /**
     * Generate namespance and use statment
     *
     * @return string
     */
    public function generateNamespaceImport() {
        $class = '';

        // Set namespace
        if (isset($this->options['namespace'])) {
            $class .= "namespace {$this->options['namespace']};\n\n";
        }

        // Set import classes
        if (isset($this->options['uses'])) {

            foreach ($this->options['uses'] as $use) {
                $class .= 'use ' . trim($use, ';') . ";\n";
            }

            $class .= "\n";
        }

        return $class;
    } 

    /**
     * Genarate class header
     *
     * @return string
     */
    public function generateClassHeader() {
        $class = "class {$this->options['className']}";

        if (isset($this->options['parent'])) {
            $class .= " extends {$this->options['parent']}";
        }

        if (isset($this->options['implements']) && !empty($this->options['implements'])) {
            $class .= ' implements';

            foreach ($this->options['implements'] as $interface) {
                $class .= $interface ? " {$interface}," : '';
            }


            $class = rtrim($class, ',');
        }

        $class .= " {\n\n";

        return $class;
    }

    /**
     * Generate class properties
     *
     * @return string
     */
    public function generateProperties() {
        $class = '';

        foreach ($this->options['fields'] as $name => $fieldData) {
            $class .= $this->generateDocBlockComment(array(
                'variable' => $fieldData['datatype'],
            ));
            $class .= $this->genereateIndentSpace() . "\${$this->getConvetedField($fieldData['name'])} \n\n";
        }

        return $class;

    }

    /**
     * Generate the class constructor and its parameters
     *
     * @return string
     */
    public function generateClassConstructor() {
        $class = '';
        $docBlockOpt = array(
            'description' => 'Class Constructor',
        );

        if (isset($this->options['constructorParameters'])) {
            $docBlockOpt['params'] = $this->options['constructorParameters'];
        }

        $class .= $this->generateDocBlockComment($docBlockOpt);
        $class .= $this->genereateIndentSpace() . 'public function __construct(';

        if (isset($this->options['constructorParameters']) && !empty($this->options['constructorParameters'])) {
            
            foreach ($this->options['constructorParameters'] as $typeHint => $parameter) {
                
                if (is_numeric($typeHint)) {
                    $class .= "{$parameter}, ";
                } else {
                    $class .= "{$typeHint} {$parameter}, ";         
                }
                
            }

            $class = rtrim($class, ', ');
        }

        return $class .= ') {}';
    }

    public function generateSetters() {

    }

    public function generateGetters() {

    }

    /**
     * Generate class template
     *
     * @return string
     */
    public function generate() {
        $class = $this->openTag();

        
        // Class namespae and imported libraries
        $class .= $this->generateNamespaceImport();

        // Class header
        $class .= $this->generateClassHeader();

        // Class Properties
        $class .= $this->generateProperties();

        // Constructor
        $class .= $this->generateClassConstructor();

        return $class;
    }

}

/**
 * Class Generator Exception class
 */
class ClassGeneratorException extends \Exception {}