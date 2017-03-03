<?php

namespace Generator\Models;

class ClassGenerator {
    
    public static function generate($options = array()) {
        $class = "<?php\n\n";

        // Set namespace
        if (isset($options['namespace'])) {
            $class .= "namespace {$options['namespace']};\n\n";
        }

        // Set import classes
        if (isset($options['uses'])) {

            foreach ($options['uses'] as $use) {
                $class .= "use {$use};\n";
            }

            $class .= "\n";

        }
        
        $class .= "class {$options['className']}" . (isset($options['parent']) ? " extends {$options['parent']} " : ' ') . "{\n\n";

        // Loop all fields
        foreach ($options['fields'] as $field) {
            $class .= "\t/**\n\t *\n\t * @var {$field['Type']}\n\t */\n";
            $class .= "\tpublic \${$field['Field']};\n\n";
        }

        // Constructor
        $class .= "\tpublic function __construct(){}";

        return $class;
    }
}