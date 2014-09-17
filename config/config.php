<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 * 
 * Copyright (c) 2014 RawPHP.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * PHP version 5.4
 * 
 * @category  PHP
 * @package   RawPHP/RawApplication
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

$config = array();

/*******************************************************************************
 * Application
 * -----------------------------------------------------------------------------
 * This is the class that will be instantiated when running the application.
 * 
 ******************************************************************************/
$config[ 'app_class' ] = 'RawPHP\RawApplication\Tests\\TestApp';

$config[ 'application_name' ] = 'Test RawPHP App';


/*******************************************************************************
 * Log
 * -----------------------------------------------------------------------------
 * These are the log settings.
 * 
 ******************************************************************************/

//use RawPHP\RawLog\Log;

//$config[ 'log' ][ 'class' ]          = 'RawPHP\\RawLog\\Log';
//$config[ 'log' ][ 'log_file' ]       = OUTPUT_DIR . 'log.txt';
//$config[ 'log' ][ 'log_name' ]       = 'test-log';
//$config[ 'log' ][ 'log_type' ][]     = Log::HANDLER_STANDARD_LOG;
//$config[ 'log' ][ 'log_type' ][]     = Log::HANDLER_ROTATE_LOG;
//$config[ 'log' ][ 'log_type' ][]     = Log::HANDLER_RAW_MAIL;


/*******************************************************************************
 * Error Handler
 * -----------------------------------------------------------------------------
 * These are the settings for the error handler.
 * 
 ******************************************************************************/
//$config[ 'error' ][ 'class' ]              = 'RawPHP\\RawErrorHandler\\ErrorHandler';
//$config[ 'error' ][ 'error_callback' ]     = array( 'RawPHP\\RawApplication\\Application', 'displayError' );
//$config[ 'error' ][ 'exception_callback' ] = array( 'RawPHP\\RawApplication\\', 'displayError' );
//$config[ 'error_handler' ][ 'shutdown_callback' ]


/*******************************************************************************
 * Request
 * -----------------------------------------------------------------------------
 * These are the settings for the request handler.
 * 
 ******************************************************************************/
$config[ 'request' ][ 'class' ]         = 'RawPHP\\RawRequest\\Request';
// there are no special configuration settings required


/*******************************************************************************
 * Router
 * -----------------------------------------------------------------------------
 * These are the settings for the router.
 * 
 ******************************************************************************/
$config[ 'router' ][ 'class' ]              = 'RawPHP\\RawRouter\\Router';
$config[ 'router' ][ 'default_controller' ] = 'home';
$config[ 'router' ][ 'default_action' ]     = 'index';
$config[ 'router' ][ 'namespace' ]          = 'RawPHP\\RawApplication\\';


/*******************************************************************************
 * Session
 * -----------------------------------------------------------------------------
 * These are the settings for the request handler.
 * 
 ******************************************************************************/
$config[ 'session' ][ 'class' ]         = 'RawPHP\\RawSession\\Session';
$config[ 'session' ][ 'auto_start' ]    = FALSE;
$config[ 'session' ][ 'strict' ]        = TRUE; // force InvalidSessionException if session problems
//$config[ 'session' ][ 'session_id' ]    = '';
//$config[ 'session' ][ 'session_path' ]  = '';


/*******************************************************************************
 * Maintenance Mode
 * -----------------------------------------------------------------------------
 * These settings dictate how maintenance mode is controlled in the application.
 * 
 * The maintenance mode can be either controlled by this configuration file, or
 * by the application.
 * 
 * - configuration file = "config"
 * - application        = "app"
 * 
 ******************************************************************************/
$config[ 'maintenance' ][ 'control' ]    = 'config'; // config | app
$config[ 'maintenance' ][ 'controller' ] = 'home';
$config[ 'maintenance' ][ 'action' ]     = 'maintain';
$config[ 'maintenance' ][ 'status' ]     = TRUE; // TRUE | FALSE to disable


/*******************************************************************************
 * Production Database
 * -----------------------------------------------------------------------------
 * These are the database settings for production|development.
 * 
 ******************************************************************************/
//$config[ 'db' ][ 'class' ]   = 'RawPHP\\RawDatabase\\Database';
//$config[ 'db' ][ 'db_name' ] = 'db_name';
//$config[ 'db' ][ 'db_user' ] = 'root';
//$config[ 'db' ][ 'db_pass' ] = '';
//$config[ 'db' ][ 'db_host' ] = 'localhost';


/*******************************************************************************
 * Test Database
 * -----------------------------------------------------------------------------
 * These are the database settings for testing.
 * 
 ******************************************************************************/
//$config[ 'test_db' ][ 'class' ]   = 'RawPHP\\RawDatabase\\Database';
//$config[ 'test_db' ][ 'db_name' ] = 'db_name_test';
//$config[ 'test_db' ][ 'db_user' ] = 'root';
//$config[ 'test_db' ][ 'db_pass' ] = '';
//$config[ 'test_db' ][ 'db_host' ] = 'localhost';


/*******************************************************************************
 * Email Settings
 * -----------------------------------------------------------------------------
 * Mail and SMTP settings used to send emails using SMTP.
 * 
 ******************************************************************************/
//$config[ 'mail' ][ 'from_email' ]   = 'no-reply@example.com';      // default from email to use in emails
//$config[ 'mail' ][ 'from_name' ]    = 'RawPHP';                    // default from name to use in emails
//
//$config[ 'mail' ][ 'smtp' ][ 'auth' ]         = TRUE;              // enable SMTP authentication
//$config[ 'mail' ][ 'smtp' ][ 'host' ]         = 'smtp.gmail.com';  // main and backup SMTP servers
//$config[ 'mail' ][ 'smtp' ][ 'username' ]     = 'username';        // SMTP username
//$config[ 'mail' ][ 'smtp' ][ 'password' ]     = 'password';        // SMTP password
//$config[ 'mail' ][ 'smtp' ][ 'security' ]     = 'ssl';             // Enable TLS encryption, 'ssl' also accepted
//$config[ 'mail' ][ 'smtp' ][ 'port' ]         = '465';             // SMTP port


return $config;