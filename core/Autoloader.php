<?php

namespace Phpify\Core;

class Autoloader
{
    public static function register()
    {
        require_once __DIR__ . '/helpers.php';

        spl_autoload_register(function ($class) {
            $prefixes = [
                'Phpify\\Core\\' => __DIR__ . '/',
                'App\\' => dirname(__DIR__) . '/app/'
            ];

            foreach ($prefixes as $prefix => $base_dir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) {
                    continue;
                }

                $relative_class = substr($class, $len);
                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

                if (file_exists($file)) {
                    require $file;
                }
            }
        });

        // Register custom error/exception handling after autoloader is registered
        \Phpify\Core\Exception\ErrorHandler::register();
    }
}
