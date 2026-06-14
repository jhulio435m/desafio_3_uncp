<?php

putenv('APP_ENV=testing');
$_ENV['APP_ENV'] = 'testing';
$_SERVER['APP_ENV'] = 'testing';

// Detect if we are running inside the Docker container
if (getenv('DB_HOST') === 'db') {
    putenv('DB_DATABASE=uncp_proyeccion_social_test');
    $_ENV['DB_DATABASE'] = 'uncp_proyeccion_social_test';
    $_SERVER['DB_DATABASE'] = 'uncp_proyeccion_social_test';
}

require __DIR__ . '/../vendor/autoload.php';
