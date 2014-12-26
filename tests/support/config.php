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
                'class'    => 'RawPHP\\RawLog\\Log',
                'debug'    => FALSE,
                'handlers' =>
                    [
                        'standard_log' =>
                            [
                                'class'     => 'RawPHP\\RawLog\\Handler\\FileHandler',
                                'file'      => 'log.txt',
                                'formatter' => 'RawPHP\\RawLog\\Formatter\\ErrorLogFormatter',
                                'level'     => 0,
                            ],
                        'rotate_log'   =>
                            [
                                'class'     => 'RawPHP\\RawLog\\Handler\\RotatingFileHandler',
                                'file'      => 'rotate.txt',
                                'formatter' => 'RawPHP\\RawLog\\Formatter\\ErrorLogFormatter',
                                'level'     => 2,
                            ],
                        'mail'         =>
                            [
                                'class'     => 'RawPHP\\RawLog\\Handler\\MailHandler',
                                'formatter' => 'RawPHP\\RawLog\\Formatter\\MailLogFormatter',
                                'level'     => 4,
                            ],

                    ],
            ],

        ################################################################################
        # Mail
        # ------------------------------------------------------------------------------
        # Mail settings.
        #
        ################################################################################

        'mail'    =>
            [
                'from_email' => 'no-reply@rawphp.org',
                'from_name'  => 'RawPHP',
                'to_address' => 'test@example.com',
                'to_name'    => 'RawPHP',
                'subject'    => 'RawPHP Error Log Message',

                'smtp'       =>
                    [
                        'auth'     => TRUE,
                        'host'     => 'smtp.gmail.com',
                        'username' => 'username',
                        'password' => 'password',
                        'security' => 'ssl',
                        'port'     => 465,
                    ],
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
                'db_name' => 'raw_database_test',
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
                'db_name' => 'raw_database_test',
                'db_user' => 'root',
                'db_pass' => '',
                'db_host' => 'localhost',
            ],

    ];
