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

namespace RawPHP\RawApplication;

use RawPHP\RawBase\Component;
use RawPHP\RawSession\Session;
use RawPHP\RawRequest\Request;
use RawPHP\RawDatabase\Database;
use RawPHP\RawErrorHandler\ErrorHandler;
use RawPHP\RawRouter\Router;
use RawPHP\RawLog\Log;
use RawPHP\RawLog\ILog;
use RawPHP\RawDatabase\IDatabase;
use RawPHP\RawBase\Exceptions\RawException;

/**
 * This is a base class for an application.
 * 
 * Framework users will subclass this class when creating 
 * their applications.
 * 
 * @category  PHP
 * @package   RawPHP/Core
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
abstract class Application extends Component
{
    public $appName                 = 'Application';
    /**
     * @var IDatabase
     */
    public $db                      = NULL;
    public $log                     = NULL;
    public $request                 = NULL;
    public $router                  = NULL;
    /**
     * @var ISession
     */
    public $session                 = NULL;
    public $errorHandler            = NULL;
    public $controller              = NULL;
    public $flash                   = array();
    public $language                = 'en_US';
    
    public $services                = array();
    
    public $defaultLanguage         = 'en_US';
    protected $defaultController    = 'home';
    protected $defaultAction        = 'index';
    
    public static $defaultTimezone  = 'Australia/Melbourne';
    
    private $_flashKey              = NULL;
    private $_flashErrorsKey        = NULL;
    private $_flashSuccessKey       = NULL;
    
    /**
     * Initialises the application.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_APP_INIT_ACTION
     * @action ON_AFTER_APP_INIT_ACTION
     */
    public function init( $config )
    {
        $this->doAction( self::ON_BEFORE_APP_INIT_ACTION );
        
        $this->_initLogger( $config );
        
        $this->_initErrorHandler( $config );
        
        $this->_initRequest( $config );
        
        $this->_initRouter( $config );
        
        $this->_initSession( $config );
        
        $this->_initDatabase( $config );
        
        $this->_initAppName( $config );
        
        $this->_initDefaultLanguage( $config );
        
        $this->_initMaintenanceMode( $config );
        
        $this->doAction( self::ON_AFTER_APP_INIT_ACTION );
    }
    
    /**
     * Initialises the log for the application.
     * 
     * @param array $config configuration array
     */
    private function _initLogger( $config )
    {
        $this->doAction( self::ON_BEFORE_LOG_INIT_ACTION );
        
        if ( isset( $config[ 'log' ] ) )
        {
            if ( isset( $config[ 'log' ][ 'class' ] ) )
            {
                $class = $config[ 'log' ][ 'class' ];
                
                $this->log = new $class( );
                $this->log->init( $config[ 'log' ] );
                
                if ( FALSE === $this->log instanceof ILog )
                {
                    throw new RawException( 'Log must implement the ILog interface.' );
                }
            }
            else
            {
                $this->log = new Log( );
                $this->log->init( $config[ 'log' ] );
            }
        }
        else
        {
            $this->log = new ErrorHandler( );
        }
        
        $this->doAction( self::ON_AFTER_LOG_INIT_ACTION );
    }
    
    /**
     * Initialises the error handler.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_ERR_HANDLER_INIT_ACTION
     * @action ON_AFTER_ERR_HANDLER_INIT_ACTION
     */
    private function _initErrorHandler( $config )
    {
        $this->doAction( self::ON_BEFORE_ERR_HANDLER_INIT_ACTION );
        
        if ( isset( $config[ 'error' ] ) )
        {
            if ( isset( $config[ 'error' ][ 'class' ] ) )
            {
                $class = $config[ 'error' ][ 'class' ];
                
                $this->errorHandler = new $class( );
                $this->errorHandler->init( $config[ 'error' ] );
            }
            else
            {
                $this->errorHandler = new ErrorHandler( );
                $this->init( $config[ 'error' ] );
            }
        }
        else
        {
            $this->errorHandler = new ErrorHandler( );
        }
        
        $this->doAction( self::ON_AFTER_ERR_HANDLER_INIT_ACTION );
    }
    
    /**
     * Initialises the request instance.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_REQUEST_INIT_ACTION
     * @action ON_AFTER_REQUEST_INIT_ACTION
     */
    private function _initRequest( $config )
    {
        $this->doAction( self::ON_BEFORE_REQUEST_INIT_ACTION );
        
        if ( isset( $config[ 'request' ] ) )
        {
            if ( isset( $config[ 'request' ][ 'class' ] ) )
            {
                $class = $config[ 'request' ][ 'class' ];
                
                $this->request = new $class( );
                $this->request->init( $config[ 'request' ] );
            }
            else
            {
                $this->request = new Request( );
                $this->request->init( $config[ 'request' ] );
            }
        }
        else
        {
            $this->request = new Request( );
        }
        
        $this->doAction( self::ON_AFTER_REQUEST_INIT_ACTION );
    }
    
    /**
     * Initialises the router.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_ROUTER_INIT_ACTION
     * @action ON_AFTER_ROUTER_INIT_ACTION
     * 
     * @filter ON_INIT_DEF_CONTROLLER_FILTER
     * @filter ON_INIT_DEF_ACTION_FILTER
     */
    private function _initRouter( $config )
    {
        $this->doAction( self::ON_BEFORE_ROUTER_INIT_ACTION );
        
        if ( isset( $config[ 'router' ] ) )
        {
            if ( isset( $config[ 'router' ][ 'class' ] ) )
            {
                $class = $config[ 'router' ][ 'class' ];
                
                $this->router = new $class( );
                $this->router->init( $config[ 'router' ] );
            }
            else
            {
                $this->router = new Router( );
                $this->router->init( $config[ 'router' ] );
            }
        }
        else
        {
            $this->router = new Router( );
        }
        
        $controller = $this->router->defaultController;
        $action     = $this->router->defaultAction;
        
        $this->router->defaultController = $this->filter( 
                self::ON_INIT_DEF_CONTROLLER_FILTER, $controller );
        
        $this->router->defaultAction = $this->filter( 
                self::ON_INIT_DEF_ACTION_FILTER, $action );
        
        $this->doAction( self::ON_AFTER_ROUTER_INIT_ACTION );
    }
    
    /**
     * Initialises the session.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_SESSION_INIT_ACTION
     * @action ON_AFTER_SESSION_INIT_ACTION
     */
    private function _initSession( $config )
    {
        $this->doAction( self::ON_BEFORE_SESSION_INIT_ACTION );
        
        if ( isset( $config[ 'session' ] ) )
        {
            if ( isset( $config[ 'session' ][ 'class' ] ) )
            {
                $class = $config[ 'session' ][ 'class' ];
                
                $this->session = new $class( );
                $this->session->init( $config[ 'session' ] );
            }
            else
            {
                $this->session = new Session( );
                $this->session->init( $config[ 'session' ] );
            }
        }
        else
        {
            $this->session = new Session( );
        }
        
        // get flash key
        $this->_flashKey = $this->filter( self::ON_GET_FLASH_KEY_FILTER, 'messages' );
        
        // get flash
        $this->flash = $this->session->get( $this->_flashKey );
        
        if ( empty( $this->flash ) )
        {
            // get flash var keys
            $this->_flashErrorsKey  = $this->filter( self::ON_GET_FLASH_ERR_KEY_FILTER, 'errors' );
            $this->_flashSuccessKey = $this->filter( self::ON_GET_FLASH_SUCC_KEY_FILTER, 'success' );
            
            $this->flash = array( 
                $this->_flashErrorsKey  => array( ), 
                $this->_flashSuccessKey => array( )
            );
        }
        
        $this->doAction( self::ON_AFTER_SESSION_INIT_ACTION );
    }
    
    /**
     * Initialises the application name.
     * 
     * @param array $config configuration array
     * 
     * @filter ON_INIT_APP_NAME_FILTER
     */
    private function _initAppName( $config )
    {
        $appName = 'Application';
        
        if ( isset( $config[ 'application_name' ] ) )
        {
            $appName = $config[ 'application_name' ];
        }
        
        $this->appName = $this->filter( self::ON_INIT_APP_NAME_FILTER, $appName );
    }
    
    /**
     * Initialises the default language used in the app.
     * 
     * @param array $config configuration array
     * 
     * @filter ON_INIT_DEFAULT_LANG_FILTER
     */
    private function _initDefaultLanguage( $config )
    {
        $language = 'en_US';
        
        if ( isset( $config[ 'default_language' ] ) )
        {
            $language = $config[ 'default_language' ];
        }
        
        $this->defaultLanguage = $this->filter( self::ON_INIT_DEFAULT_LANG_FILTER, $language );
    }
    
    /**
     * Initialises the database.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_DATABASE_INIT_ACTION
     * @action ON_AFTER_DATABSE_INIT_ACTION
     */
    private function _initDatabase( $config )
    {
        $this->doAction( self::ON_BEFORE_DATABASE_INIT_ACTION );
        
        if ( file_exists( TEST_LOCK_FILE ) )
        {
            echo PHP_EOL . PHP_EOL . '************* RAW_TESTING *************' . PHP_EOL . PHP_EOL;
            
            $dbKey = 'test_db';
        }
        else
        {
            $dbKey = 'db';
        }
        
        if ( isset( $config[ $dbKey ] ) )
        {
            if ( isset( $config[ $dbKey ][ 'class' ] ) )
            {
                $class = $config[ $dbKey ][ 'class' ];
                
                $this->db = new $class( );
                $this->db->init( $config[ $dbKey ] );
                
                if ( FALSE === $this->db instanceof IDatabase )
                {
                    throw new RawException( 'The database class must implement the IDatabase interface.' );
                }
            }
            else
            {
                $this->db = new Database( );
                $this->db->init( $config[ $dbKey ] );
            }
        }
        
        $this->doAction( self::ON_AFTER_DATABSE_INIT_ACTION );
    }
    
    /**
     * Initialises the maintenance mode system.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_MAINTAINT_MODE_ACTION
     * @action ON_MAINTENANCE_MODE_ACTION
     * @action ON_AFTER_MAINTAIN_MODE_ACTION
     * 
     * @filter ON_GET_MAINTAIN_MODE_ROUTE_FILTER
     */
    private function _initMaintenanceMode( $config )
    {
        $this->doAction( self::ON_BEFORE_MAINTAINT_MODE_ACTION );
        
        if ( !file_exists( TEST_LOCK_FILE ) )
        {
            if ( isset( $this->config[ 'maintenance' ] ) )
            {
                if ( isset( $this->config[ 'maintenance' ][ 'control' ] ) 
                        && 'app' === $this->config[ 'maintenance' ][ 'control' ] )
                {
                    $this->doAction( self::ON_MAINTENANCE_MODE_ACTION );
                }
                elseif ( isset( $this->config[ 'maintenance' ][ 'control' ] ) 
                        && 'config' === $this->config[ 'maintenance' ][ 'control' ] )
                {
                    if ( isset( $this->config[ 'maintenance' ][ 'status' ] ) 
                            && TRUE === $this->config[ 'maintenance' ][ 'status' ] )
                    {
                        $route = $this->config[ 'maintenance' ][ 'controller' ] 
                                . '/' . $this->config[ 'maintenance' ][ 'action' ];
                        
                        $route = $this->filter( self::ON_GET_MAINTAIN_MODE_ROUTE_FILTER, $route,
                                $this->config[ 'maintenance' ][ 'controller' ],
                                $this->config[ 'maintenance' ][ 'action' ]
                        );
                        
                        if ( 0 !== strcmp( $route, $this->request->route ) )
                        {
                            header( 'Location:' . $this->createUrl( $route ) );
                            exit( );
                        }
                    }
                }
            }
        }
        
        $this->doAction( self::ON_AFTER_MAINTAIN_MODE_ACTION );
    }
    
    /**
     * Initialises the application request process.
     * 
     * @action ON_BEFORE_RUN_ACTION
     * @action ON_AFTER_RUN_ACTION
     */
    public function run()
    {
        $this->doAction( self::ON_BEFORE_RUN_ACTION );
        
        $this->processRequest();
        
        $this->doAction( self::ON_AFTER_RUN_ACTION );
    }
    
    /**
     * Processes the HTTP request.
     * 
     * @action ON_BEFORE_PROCESS_REQUEST_ACTION
     * @action ON_AFTER_PROCESS_REQUEST_ACTION
     * 
     * @throws Exception
     */
    protected function processRequest()
    {
        $this->doAction( self::ON_BEFORE_PROCESS_REQUEST_ACTION );
        
        $route  = $this->request->route;
        $params = $this->request->params;
        
        $controller = $this->router->createController( $route, $params );
        
        if ( NULL === $controller )
        {
            throw new Exception( 'Failed to Initialise Controller', 404 );
        }
        
        $this->controller = $controller;
        
        $controller->run( );
        
        $this->doAction( self::ON_AFTER_PROCESS_REQUEST_ACTION );
    }
    
    /**
     * Creates a new url.
     * 
     * @param string $route    controller/method route
     * @param array  $params   list of parameters
     * @param bool   $absolute whether it should be an absolute url
     * 
     * @return string the url
     */
    public function createUrl( $route, $params = array(), $absolute = FALSE )
    {
        return $this->request->createUrl( $route, $params, $absolute );
    }
    
    /**
     * Adds a flash message to appear on the next page.
     * 
     * Error   => 'error' ( default )
     * Success => 'success'
     * 
     * @param string $message success or error message
     * @param string $type    the type of message
     */
    public function addFlash( $message, $type = 'error' )
    {
        if ( 'error' === $type )
        {
            $this->flash[ 'errors' ][] = $message;
        }
        else
        {
            $this->flash[ 'success' ][] = $message;
        }
        
        $this->session->add( 'messages', $this->flash );
    }
    
    /**
     * Returns a list of error flash messages.
     * 
     * @filter ON_GET_ERRORS_FILTER
     * 
     * @return array list of error messages
     */
    public function getErrors( )
    {
        $retVal = $this->flash[ 'errors' ];
        
        return $this->filter( self::ON_GET_ERRORS_FILTER, $retVal );
    }
    
    /**
     * Returns a list of success flash messages.
     * 
     * @filter ON_GET_MESSAGES_FILTER
     * 
     * @return array list of success messages
     */
    public function getMessages( )
    {
        $retval = $this->flash[ 'success' ];
        
        return $this->filter( self::ON_GET_MESSAGES_FILTER, $retval );
    }
    
    /**
     * This function outputs caught errors and exceptions.
     * 
     * This method can be overriden in a subclass to change the
     * error output format.
     * 
     * @param array $error the error array
     */
    public static function displayError( $error )
    {
        echo '<pre>';
        print_r( $error );
        echo '</pre>';
        die();
    }
    
    /**
     * Removes old flash messages.
     */
    public function cleanFlash( )
    {
        $this->flash[ 'success' ] = array();
        $this->flash[ 'errors' ]  = array();
        
        $this->session->add( 'messages', $this->flash );
    }
    
    /**
     * Checks whether maintenance mode is enabled.
     * 
     * If maintance mode is active, it will redirect to the maintenace page
     * as defined in the configuration file.
     * 
     * @action ON_MAINTENANCE_MODE_ACTION
     */
    protected function checkMaintenanceMode( )
    {
        if ( isset( $this->config[ 'maintenance' ] ) )
        {
            if ( isset( $this->config[ 'maintenance' ][ 'control' ] ) 
                    && 'app' === $this->config[ 'maintenance' ][ 'control' ] )
            {
                $this->doAction( self::ON_MAINTENANCE_MODE_ACTION );
            }
            elseif ( isset( $this->config[ 'maintenance' ][ 'control' ] ) 
                    && 'config' === $this->config[ 'maintenance' ][ 'control' ] )
            {
                if ( isset( $this->config[ 'maintenance' ][ 'status' ] ) 
                        && TRUE === $this->config[ 'maintenance' ][ 'status' ] )
                {
                    $route = $this->config[ 'maintenance' ][ 'controller' ] 
                            . '/' . $this->config[ 'maintenance' ][ 'action' ];
                    
                    if ( 0 !== strcmp( $route, $this->request->route ) )
                    {
                        header( 'Location:' . $this->createUrl( $route ) );
                        exit( );
                    }
                }
            }
        }
    }
    
    // actions
    const ON_CREATE_CONTROLLER_FILTER           = 'create_controller_filter';
    const ON_GET_MESSAGES_FILTER                = 'on_get_messages_filter';
    const ON_GET_ERRORS_FILTER                  = 'on_get_errors_filter';
    
    const ON_BEFORE_APP_INIT_ACTION             = 'on_before_app_init_action';
    const ON_AFTER_APP_INIT_ACTION              = 'on_after_app_init_action';
    
    const ON_BEFORE_LOG_INIT_ACTION             = 'on_before_log_init_action';
    const ON_AFTER_LOG_INIT_ACTION              = 'on_after_log_init_action';
    
    const ON_BEFORE_ERR_HANDLER_INIT_ACTION     = 'on_before_err_handler_init_action';
    const ON_AFTER_ERR_HANDLER_INIT_ACTION      = 'on_after_err_handler_init_action';
    
    const ON_BEFORE_REQUEST_INIT_ACTION         = 'on_before_request_init_action';
    const ON_AFTER_REQUEST_INIT_ACTION          = 'on_after_request_init_action';
    
    const ON_BEFORE_ROUTER_INIT_ACTION          = 'on_before_router_init_action';
    const ON_AFTER_ROUTER_INIT_ACTION           = 'on_after_router_init_action';
    
    const ON_BEFORE_SESSION_INIT_ACTION         = 'on_before_session_init_action';
    const ON_AFTER_SESSION_INIT_ACTION          = 'on_after_session_init_action';
    
    const ON_BEFORE_DATABASE_INIT_ACTION        = 'on_before_database_init_action';
    const ON_AFTER_DATABSE_INIT_ACTION          = 'on_after_database_init_action';
    
    const ON_MAINTENANCE_MODE_ACTION            = 'maintenance_mode_action';
    const ON_BEFORE_MAINTAINT_MODE_ACTION       = 'on_before_maintain_mode_action';
    const ON_AFTER_MAINTAIN_MODE_ACTION         = 'on_after_maintain_mode_action';
    
    const ON_BEFORE_RUN_ACTION                  = 'before_run_action';
    const ON_AFTER_RUN_ACTION                   = 'after_run_action';
    const ON_BEFORE_PROCESS_REQUEST_ACTION      = 'before_process_request';
    const ON_AFTER_PROCESS_REQUEST_ACTION       = 'after_process_request';
    
    // filters
    const ON_GET_FLASH_KEY_FILTER               = 'on_get_flash_key_filter';
    const ON_GET_FLASH_ERR_KEY_FILTER           = 'on_get_flash_err_key_filter';
    const ON_GET_FLASH_SUCC_KEY_FILTER          = 'on_get_flash_success_key_filter';
    
    const ON_INIT_APP_NAME_FILTER               = 'on_init_app_name_filter';
    const ON_INIT_DEFAULT_LANG_FILTER           = 'on_init_default_lang_filter';
    
    const ON_INIT_DEF_CONTROLLER_FILTER         = 'on_init_def_controller_filter';
    const ON_INIT_DEF_ACTION_FILTER             = 'on_init_def_action_filter';
    
    const ON_GET_MAINTAIN_MODE_ROUTE_FILTER     = 'on_get_maintan_mode_route_filter';
}
