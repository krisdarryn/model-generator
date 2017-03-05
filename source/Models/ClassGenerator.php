<?php

namespace Generator\Models;

class ClassGenerator {
    
    public static function generate($options = array()) {
        $class = "<?php\n\n";

        
        // Class namespae and imported libraries
        $class .= self::generateNamespaceImport($options);

        // Class header
        $class .= self::generateClassHeader($options);

        // Class Properties
        $class .= self::generateClassProperties($options);

        // Constructor
        $class .= self::generateClassConstructor();

        $class .= "\n\n}";

        return $class;
    }

    public static function generateNamespaceImport($options) {
        $class = '';

        // Set namespace
        if (isset($options['namespace'])) {
            $class .= "namespace {$options['namespace']};\n\n";
        }

        // Set import classes
        if (isset($options['uses'])) {

            foreach ($options['uses'] as $use) {
                $class .= 'use ' . trim($use, ';') . ";\n";
            }

            $class .= "\n";
        }

        debug_r($options);

        return $class;
    } 

    public static function generateClassHeader($options) {
        $class = "class {$options['className']}";

        if (isset($options['parent'])) {
            $class .= " extends {$options['parent']}";
        }

        if (isset($options['implements']) && !empty($options['implements'])) {

            $class .= ' implements';
            foreach ($options['implements'] as $interface) {
                $class .= $interface ? " {$interface}," : '';
            }


            $class = rtrim($class, ',');
        }

        $class .= " {\n\n";

        return $class;
    }

    public static function generateClassProperties($options) {
        $class = '';

        if (isset($options['fields'])) {
            
            foreach ($options['fields'] as $field) {
                $class .= "\t/**\n\t *\n\t * @var {$field['Type']}\n\t */\n";
                $class .= "\tpublic \${$field['Field']};\n\n";
            }
            
        }

        return $class;
    }

    public static function generateClassConstructor() {
        /*$class = "\t/**\n\t *\n\t * Constructor\n";

        if (isset($options['constructorParameters']) && !empty($options['constructorParameters'])) {
            $class .= ""
        }*/


        return "\t/**\n\t *\n\t * Constructor\n\t */\n\tpublic function __construct() {}";
    }

}