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
 * @package   RawPHP\RawApplication
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawApplication;

use RawPHP\RawApplication\Contract\IApplication;
use RawPHP\RawContainer\Container;
use RawPHP\RawRequest\Request;
use RawPHP\RawRouter\Contract\IController;
use RawPHP\RawRouter\Router;
use RawPHP\RawSession\Session;
use RawPHP\RawSupport\Exception\RawException;

/**
 * This is a base class for an application.
 *
 * Framework users will subclass this class when creating
 * their applications.
 *
 * @category  PHP
 * @package   RawPHP\RawApplication
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
abstract class Application extends Container implements IApplication
{
    /** @var  string */
    protected $appName;
    /** @var  IController */
    protected $controller = NULL;
    /** @var  string */
    protected $language = 'en_US';
    /** @var  string */
    protected $defaultLanguage = 'en_US';
    /** @var  string */
    protected $defaultController = 'home';
    /** @var  string */
    protected $defaultAction = 'index';

    /** @var  array */
    protected $flash = [ ];

    /** @var  string */
    public static $defaultTimezone = 'Australia/Melbourne';

    /**
     * Create new application.
     *
     * @param array $config
     */
    public function __construct( $config = [ ] )
    {
        $this->init( $config );
    }

    /**
     * Initialises the application.
     *
     * @param array $config configuration array
     */
    public function init( $config = [ ] )
    {
        $this->initRequest( $config );

        $this->initRouter( $config );

        $this->initSession( $config );

        $this->initAppName( $config );

        $this->initDefaultLanguage( $config );

//        $this->_initMaintenanceMode( $config );
    }

    /**
     * Get the application name.
     *
     * @return string
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * Initialises the request instance.
     *
     * @param array $config configuration array
     */
    protected function initRequest( array $config )
    {
        if ( isset( $config[ 'request' ] ) )
        {
            if ( isset( $config[ 'request' ][ 'class' ] ) )
            {
                $this->bindShared( 'RawPHP\RawRequest\Contract\IRequest', function () use ( $config )
                {
                    $class = $config[ 'request' ][ 'class' ];
                    /** @var Request $request */
                    $request = new $class();
                    $request->init( $config[ 'request' ] );

                    return $request;
                }
                );
            }
            else
            {
                $this->bindShared( 'RawPHP\RawRequest\Contract\IRequest', function () use ( $config )
                {
                    $request = new Request();
                    $request->init( $config[ 'request' ] );

                    return $request;
                }
                );
            }
        }
        else
        {
            $this->bindShared( 'RawPHP\RawRequest\Contract\IRequest', function ()
            {
                $request = new Request();
                $request->init();

                return $request;
            }
            );
        }

        $this->alias( 'RawPHP\RawRequest\Contract\IRequest', 'request' );
    }

    /**
     * Initialises the router.
     *
     * @param array $config configuration array
     */
    protected function initRouter( array $config )
    {
        if ( isset( $config[ 'router' ] ) )
        {
            if ( isset( $config[ 'router' ][ 'class' ] ) )
            {
                $this->bindShared( 'RawPHP\RawRouter\Contract\IRouter', function () use ( $config )
                {
                    $class = $config[ 'router' ][ 'class' ];

                    return new $class( $config[ 'router' ] );
                }
                );
            }
            else
            {
                $this->bindShared( 'RawPHP\RawRouter\Contract\IRouter', function () use ( $config )
                {
                    return new Router( $config[ 'router' ] );
                }
                );
            }
        }
        else
        {
            $this->bindShared( 'RawPHP\RawRouter\Contract\IRouter', function ()
            {
                return new Router();
            }
            );
        }

        $this->alias( 'RawPHP\RawRouter\Contract\IRouter', 'RawPHP\RawRouter\Router' );
        $this->alias( 'RawPHP\RawRouter\Contract\IRouter', 'router' );

        $this->defaultController = $this[ 'router' ]->getDefaultController();
        $this->defaultAction     = $this[ 'router' ]->getDefaultAction();
    }

    /**
     * Initialises the session.
     *
     * @param array $config configuration array
     */
    protected function initSession( array $config )
    {
        if ( isset( $config[ 'session' ] ) )
        {
            if ( isset( $config[ 'session' ][ 'class' ] ) )
            {
                $this->bindShared( 'RawPHP\RawSession\Contract\ISession', function () use ( $config )
                {
                    $class = $config[ 'session' ][ 'class' ];

                    return new $class( $config[ 'session' ] );
                }
                );
            }
            else
            {
                $this->bindShared( 'RawPHP\RawSession\Contract\ISession', function () use ( $config )
                {
                    return new Session( $config[ 'session' ] );
                }
                );
            }
        }
        else
        {
            $this->bindShared( 'RawPHP\RawSession\Contract\ISession', function ()
            {
                return new Session();
            }
            );
        }

        $this->alias( 'RawPHP\RawSession\Contract\ISession', 'session' );

        $this->flash = $this[ 'session' ]->get( 'messages' );

        if ( empty( $this->flash ) )
        {
            $this->flash = [
                'errors'  => [ ],
                'success' => [ ],
            ];
        }
    }

    /**
     * Initialises the application name.
     *
     * @param array $config configuration array
     */
    protected function initAppName( array $config )
    {
        $appName = 'Application';

        if ( isset( $config[ 'app' ][ 'name' ] ) )
        {
            $appName = $config[ 'app' ][ 'name' ];
        }

        $this->appName = $appName;
    }

    /**
     * Initialises the default language used in the app.
     *
     * @param array $config configuration array
     */
    protected function initDefaultLanguage( array $config )
    {
        $language = 'en_US';

        if ( isset( $config[ 'default_language' ] ) )
        {
            $language = $config[ 'default_language' ];
        }

        $this->defaultLanguage = $language;
    }

    /**
     * Initialises the maintenance mode system.
     *
     * @param array $config configuration array
     */
    protected function _initMaintenanceMode( array $config )
    {
//        if ( !file_exists( TEST_LOCK_FILE ) )
//        {
//            if ( isset( $this->config[ 'maintenance' ] ) )
//            {
//                if ( isset( $this->config[ 'maintenance' ][ 'control' ] )
//                    && 'app' === $this->config[ 'maintenance' ][ 'control' ]
//                )
//                {
//                    $this->doAction( self::ON_MAINTENANCE_MODE_ACTION );
//                }
//                elseif ( isset( $this->config[ 'maintenance' ][ 'control' ] )
//                    && 'config' === $this->config[ 'maintenance' ][ 'control' ]
//                )
//                {
//                    if ( isset( $this->config[ 'maintenance' ][ 'status' ] )
//                        && TRUE === $this->config[ 'maintenance' ][ 'status' ]
//                    )
//                    {
//                        $route = $this->config[ 'maintenance' ][ 'controller' ]
//                            . '/' . $this->config[ 'maintenance' ][ 'action' ];
//
//                        $route = $this->filter( self::ON_GET_MAINTAIN_MODE_ROUTE_FILTER, $route,
//                                                $this->config[ 'maintenance' ][ 'controller' ],
//                                                $this->config[ 'maintenance' ][ 'action' ]
//                        );
//
//                        if ( 0 !== strcmp( $route, $this->request->route ) )
//                        {
//                            header( 'Location:' . $this->createUrl( $route ) );
//                            exit();
//                        }
//                    }
//                }
//            }
//        }
    }

    /**
     * Initialises the application request process.
     */
    public function run()
    {
        $this->processRequest();
    }

    /**
     * Processes the HTTP request.
     *
     * @throws RawException
     */
    protected function processRequest()
    {
        $route  = $this[ 'request' ]->getRoute();
        $params = $this[ 'request' ]->getParams();

        $controller = $this[ 'router' ]->createController( $route, $params );

        if ( NULL === $controller )
        {
            throw new RawException( 'Failed to Initialise Controller', 404 );
        }

        $this->controller = $controller;

        /** @var IController $controller */
        $controller->run();
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
    public function createUrl( $route, $params = [ ], $absolute = FALSE )
    {
        return $this[ 'request' ]->createUrl( $route, $params, $absolute );
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
            $this->flash[ 'errors' ][ ] = $message;
        }
        else
        {
            $this->flash[ 'success' ][ ] = $message;
        }

        //$this[ 'session' ]->add( 'messages', $this->flash );
    }

    /**
     * Returns a list of error flash messages.
     *
     * @return array list of error messages
     */
    public function getErrors()
    {
        $retVal = $this->flash[ 'errors' ];

        return $retVal;
    }

    /**
     * Returns a list of success flash messages.
     *
     * @return array list of success messages
     */
    public function getMessages()
    {
        return $this->flash[ 'success' ];
    }

    /**
     * Get the flash messages array.
     *
     * @return array
     */
    public function getFlash()
    {
        return $this->flash;
    }

    /**
     * Set the flash messages array.
     *
     * @param array $flash
     */
    public function setFlash( array $flash )
    {
        $this->flash = $flash;
    }

    /**
     * This function outputs caught errors and exceptions.
     *
     * This method can be overridden in a subclass to change the
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
    public function cleanFlash()
    {
        $this->flash[ 'success' ] = [ ];
        $this->flash[ 'errors' ]  = [ ];

        $this[ 'session' ]->add( 'messages', $this->flash );
    }

    /**
     * Checks whether maintenance mode is enabled.
     *
     * If maintenance mode is active, it will redirect to the maintenace page
     * as defined in the configuration file.
     */
    protected function checkMaintenanceMode()
    {
//        if ( isset( $this->config[ 'maintenance' ] ) )
//        {
//            if ( isset( $this->config[ 'maintenance' ][ 'control' ] )
//                && 'app' === $this->config[ 'maintenance' ][ 'control' ]
//            )
//            {
//                $this->doAction( self::ON_MAINTENANCE_MODE_ACTION );
//            }
//            elseif ( isset( $this->config[ 'maintenance' ][ 'control' ] )
//                && 'config' === $this->config[ 'maintenance' ][ 'control' ]
//            )
//            {
//                if ( isset( $this->config[ 'maintenance' ][ 'status' ] )
//                    && TRUE === $this->config[ 'maintenance' ][ 'status' ]
//                )
//                {
//                    $route = $this->config[ 'maintenance' ][ 'controller' ]
//                        . '/' . $this->config[ 'maintenance' ][ 'action' ];
//
//                    if ( 0 !== strcmp( $route, $this->request->route ) )
//                    {
//                        header( 'Location:' . $this->createUrl( $route ) );
//                        exit();
//                    }
//                }
//            }
//        }
    }
}