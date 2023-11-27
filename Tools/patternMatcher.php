<?php

class PatternMatcherException extends Exception {}
class PatternMatcher {
    public static function matchPattern(mixed $var, string $jsonPattern) {
        $refObj = json_decode($jsonPattern);
        $path = '/';

        // wip

        function recur(mixed $var, mixed $refObj, string $path)
        {
            if (gettype($var) !== gettype($refObj)) {
                throw new PatternMatcherException("Type mismatch at $path: ref is " . gettype($refObj) . ' but got ' . gettype($var));
            }

            switch (gettype($var)) {
                case 'object':
                    $reflection = new ReflectionObject($var);
                    foreach ($reflection->getProperties() as $prop) {
                        var_dump($prop->getName());
                    }
            }
        }

        recur($var, $refObj, $path);
    }
}
$obj = (new stdClass());
$obj->a = new stdClass();
PatternMatcher::matchPattern($obj, '{"a":{}}');