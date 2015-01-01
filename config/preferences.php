<?php

return array(
    /**
     * Application timezone
     * --------------------
     */
    'timezone' => 'America/Sao_Paulo',

    /**
     * Debug mode
     * ----------
     * If true, outputs all database queries and
     * requests on application's log file.
     */
    'debug' => true,

    /**
     * Cache
     * -----
     *
     * Available options:
     *
     * - filesystem
     * - database
     */
    'cache' => 'database',

    'aliases' => array(
        'Controller' => 'Hook\\Framework\\Controller',

        // Hook\Model
        'App' => 'Hook\\Model\\App',
        'AppKey' => 'Hook\\Model\\AppKey',
        'Collection' => 'Hook\\Model\\Collection',

        // Hook\Application
        'Context' => 'Hook\\Application\\Context',
        'Config' => 'Hook\\Application\\Config',

        // Hook\Http
        'Cookie' => 'Hook\\Http\\Cookie',
        'Input' => 'Hook\\Http\\Input',
        'Request' => 'Hook\\Http\\Request',
        'Response' => 'Hook\\Http\\Response',
        'Router' => 'Hook\\Http\\Router',

        // Utils
        'Mail' => 'Hook\\Mailer\\Mail'
    )

);
