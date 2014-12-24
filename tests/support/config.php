<?php

return
    [

        ################################################################################
        # Application
        # ------------------------------------------------------------------------------
        # Application settings.
        #
        ################################################################################

        'app'     =>
            [
                'debug'            => FALSE,
                'class'            => 'RawPHP\\RawApplication\\Support\\TestApp',
                'name'             => 'Test RawPHP App',
                'default_language' => 'en_US',
            ],

        ################################################################################
        # Log
        # ------------------------------------------------------------------------------
        # Log settings.
        #
        ################################################################################

        'log'     =>
            [
                'debug'    => FALSE,
                'class'    => 'RawPHP\\RawLog\\Log',
                'log_file' => '',
                'log_name' => 'test - log',
            ],

        ################################################################################
        # Error Handler
        # ------------------------------------------------------------------------------
        # Error Handler settings.
        #
        ################################################################################

        'error'   =>
            [
                'debug'              => FALSE,
                'error_callback'     =>
                    [

                        'class'  => 'RawPHP\\RawApplication\\Application',
                        'method' => 'displayError',
                    ],
                'exception_callback' =>
                    [
                        'class'  => 'RawPHP\\RawApplication\\Application',
                        'method' => 'displayError'
                    ],
                'shutdown_callback'  => '',
            ],

        ################################################################################
        # Request
        # ------------------------------------------------------------------------------
        # Request settings.
        #
        ################################################################################

        'request' =>
            [
                'class' => 'RawPHP\\RawRequest\\Request',
                'debug' => TRUE,
            ],

        ################################################################################
        # Router
        # ------------------------------------------------------------------------------
        # Router settings.
        #
        ################################################################################

        'router'  =>
            [
                'class'              => 'RawPHP\\RawRouter\\Router',
                'default_controller' => 'home',
                'default_action'     => 'index',
                'namespaces'         =>
                    [
                        'RawPHP\\RawRouter\\Support\\Controller\\',
                    ],
                'debug'              => TRUE,
            ],

        ################################################################################
        # Session
        # ------------------------------------------------------------------------------
        # Session settings.
        #
        ################################################################################

        'session' =>
            [
                'class'        => 'RawPHP\\RawSession\\Session',
                'handler'      => 'file', // [ 'file', 'php' ]
                'auto_start'   => FALSE,
                'strict'       => TRUE,
                'session_path' => OUTPUT_DIR . 'session.json',
                'session_id'   => '',
            ],

        ################################################################################
        # Maintenance Mode
        # ------------------------------------------------------------------------------
        # Maintenance mode settings.
        #
        ################################################################################

//        maintenance:
//    debug:      FALSE
//    control:    config
//    controller: home
//    action:     maintain
//    status:     FALSE

        ################################################################################
        # Production Database
        # ------------------------------------------------------------------------------
        # These are the database settings for production|development
        #
        ################################################################################

        'db'      =>
            [
                'debug'   => FALSE,
                'class'   => 'RawPHP\\RawDatabase\\MySql',
                'db_name' => 'db_name',
                'db_user' => 'root',
                'db_pass' => '',
                'db_host' => 'localhost',
            ],

        ################################################################################
        # Test Database
        # ------------------------------------------------------------------------------
        # These are the database settings for testing
        #
        ################################################################################

        'test_db' =>
            [
                'debug'   => FALSE,
                'class'   => 'RawPHP\\RawDatabase\\MySql',
                'db_name' => 'db_name_test',
                'db_user' => 'root',
                'db_pass' => '',
                'db_host' => 'localhost',
            ],

    ];
