<?php

return [
    'name' => 'App Migrations',
    'migrations_namespace' => 'App\Migrations',
    'table_name' => 'doctrine_migration_versions',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => __DIR__ . '/src/Migrations/',
    'check_database_platform' => true,
];
