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
     * @var int
     */
    const UPPER_CAMEL_CASE = 2;

    /**
     * @var int
     */
    const ACS_MOD_PUBLIC = 0;

    /**
     * @var int
     */
    const ACS_MOD_PRIVATE = 1;

    /**
     * @var int
     */
    const ACS_MOD_PROTECTED = 2;

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

            if (!isset($annotations['params'])) {
                $comment .= $this->genereateIndentSpace() . " *\n";
            }

            $comment .= $this->genereateIndentSpace() . " * @return {$annotations['return']}\n";
        }

        return $comment . $this->genereateIndentSpace() . " */\n";
    }

    /**
     * Convert field to the appropriate naming convention
     *
     * @param string $fieldName
     * @param null|int $namingOpt
     * @return string
     */
    public function getConvetedField($fieldName, $namingOpt = null) {
        
        if (isset($this->options['fieldNamingConvention']) || $namingOpt) {
            $namingOpt? : $this->options['fieldNamingConvention'];

            switch ($namingOpt) {
                case self::LOWERCASE_SEPARATED_BY_UNDERSCORE:
                    $fieldName = strtolower(preg_replace('/[-_ ]/', '_', $fieldName));
                    break;
                

                case self::LOWER_CAMEL_CASE;
                    $fieldName = str_replace(' ', '', lcfirst(ucwords(preg_replace('/[-_ ]/', ' ', $fieldName))));
                    break;

                case self::UPPER_CAMEL_CASE:
                    $fieldName = str_replace(' ', '', ucfirst(ucwords(preg_replace('/[-_ ]/', ' ', $fieldName))));

            }
        }
        
        return $fieldName;
    }

    /**
     * Get the access modifier
     *
     * @param int $acsMod
     * @return string
     */
    public function getAccessModifier($acsMod = null) {
        $acsMod?: self::ACS_MOD_PUBLIC;
        $output = 'public';

        switch ($acsMod) {
            case self::ACS_MOD_PRIVATE:
                $output = 'public';
                break;

            case self::ACS_MOD_PROTECTED:
                $output = 'protected';
        }

        return $output;
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
            $class .= $this->genereateIndentSpace() . $this->getAccessModifier() . " \${$this->getConvetedField($fieldData['name'])} \n\n";
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

        return $class .= ") {}\n\n";
    }

    /**
     * Generate class property setters
     *
     * @return string
     */
    public function generateSetters() {
        $class = '';

        foreach ($this->options['fields'] as $name => $fieldData) {

            $class .= $this->generateDocBlockComment(array(
                'description' => "Setter for {$fieldData['name']}",
                'params' => [
                    $fieldData['datatype'] => "\${$fieldData['name']}"
                ]
            ));
            $class .= $this->genereateIndentSpace() . 'public function set' . $this->getConvetedField($fieldData['name'], self::UPPER_CAMEL_CASE);
            $class .= '($' . $this->getConvetedField($fieldData['name']) . ") {\n";
            $class .= $this->genereateIndentSpace() . $this->genereateIndentSpace() . '$this->' . $fieldData['name'] ." = \${$fieldData['name']}\n";
            $class .= $this->genereateIndentSpace() . "}\n\n";

        }

        return $class;
    }

    /**
     * Generate class property getters
     *
     * @return string
     */
    public function generateGetters() {
        $class = '';

        foreach ($this->options['fields'] as $name => $fieldData) {

            $class .= $this->generateDocBlockComment(array(
                'description' => "Get {$fieldData['name']}",
                'return' => $fieldData['datatype']
            ));
            $class .= $this->genereateIndentSpace() . 'public function get' . $this->getConvetedField($fieldData['name'], self::UPPER_CAMEL_CASE);
            $class .= "() {\n";
            $class .= $this->genereateIndentSpace() . $this->genereateIndentSpace() . ' return $this->' . $fieldData['name'] ."\n";
            $class .= $this->genereateIndentSpace() . "}\n\n";

        }

        return $class;
    }

    /**
     * Clossin class bracket
     * 
     * @return string
     */
    public function closeClass() {
        return '}';
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

        // Setters 
        if (isset($this->options['setters']) && $this->options['setters']) {
            $class .= $this->generateSetters();
        }

        // Getters
        if (isset($this->options['getters']) && $this->options['getters']) {
            $class .= $this->generateGetters();
        }

        // Close class template
        $class .= $this->closeClass();

        return $class;
    }

}

/**
 * Class Generator Exception class
 */
class ClassGeneratorException extends \Exception {}