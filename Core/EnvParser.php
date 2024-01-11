<?php

namespace Core;

class EnvParser {
    public function __construct() {
        $envFilePath = DOC_ROOT . "/.env";

        // Check if the file exists
        if (!file_exists($envFilePath)) {
            die('.env file not found');
        }

        // Read and parse the file
        $envContent = file_get_contents($envFilePath);
        $lines = explode(PHP_EOL, $envContent);

        foreach ($lines as $line) {
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Split the line into key and value
            list($key, $value) = explode('=', $line, 2);

            // Set the environment variable
            putenv("$key=$value");
        }
    }
}