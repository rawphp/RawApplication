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
 * @package   RawPHP/RawApplication/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawApplication;

use RawPHP\RawApplication\Tests\TestApp;
use RawPHP\RawSession\Session;

/**
 * The application tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawApplication/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $app;
    
    /**
     * Setup before test suite run.
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        touch( TEST_LOCK_FILE );
    }
    
    /**
     * Cleanup after test suite run.
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        
        if ( file_exists( TEST_LOCK_FILE ) )
        {
            unlink( TEST_LOCK_FILE );
        }
    }
    
    /**
     * Setup before each test.
     * 
     * @global type $config
     */
    protected function setUp()
    {
        global $config;
        
        $this->app = new TestApp( );
        $this->app->init( $config );
    }
    
    /**
     * Cleanup after each test.
     */
    protected function tearDown()
    {
        $this->app->flash[ 'errors' ]  = array();
        $this->app->flash[ 'success' ] = array();
        $this->app = NULL;
    }
    
    /**
     * Test application instantiated correctly.
     */
    public function testApplicationInstanceIsCorrectlyInitialised()
    {
        $this->assertNotNull( $this->app );
    }
    
    /**
     * Test request setup correctly.
     * 
     * @global array $config configuration array
     */
    public function testRequestSet( )
    {
        global $config;
        
        $this->assertNotNull( $this->app->request );
        
        $this->assertEquals( $config[ 'request' ][ 'class' ], get_class( $this->app->request ) );
    }
    
    /**
     * Test router setup correctly.
     * 
     * @global array $config configuration array
     */
    public function testRouterSet( )
    {
        global $config;
        
        $this->assertNotNull( $this->app->router );
        
        $this->assertEquals( $config[ 'router' ][ 'class' ], get_class( $this->app->router ) );
        $this->assertEquals( $config[ 'router' ][ 'default_controller' ], $this->app->router->defaultController );
        $this->assertEquals( $config[ 'router' ][ 'default_action' ], $this->app->router->defaultAction );
        $this->assertEquals( $config[ 'router' ][ 'namespace' ], $this->app->router->namespace );
    }
    
    /**
     * Test session setup correctly.
     * 
     * @global array $config configuration array
     */
    public function testSessionSet( )
    {
        global $config;
        
        $this->assertNotNull( $this->app->session );
        
        $this->assertEquals( $config[ 'session' ][ 'class' ], get_class( $this->app->session ) );
        
        $this->assertEquals( Session::STATUS_NONE, $this->app->session->getStatus( ) );
    }
    
    /**
     * Test application name set.
     * 
     * @global array $config configuration array
     */
    public function testApplicationNameSet( )
    {
        global $config;
        
        $this->assertEquals( $config[ 'application_name' ], $this->app->appName );
    }
    
    /**
     * Test create url.
     */
    public function testCreateUrl()
    {
        $route = 'home';
        $expected = 'home';
        
        $result = $this->app->createUrl( $route );
        
        $this->assertEquals( $expected, $result );
    }
    
    /**
     * Test create absolute url.
     */
    public function testCreateAbsoluteUrl( )
    {
        $route = 'home';
        $expected = BASE_URL . 'home';
        
        $result = $this->app->createUrl( $route, NULL, TRUE );
        
        $this->assertEquals( $expected, $result );
    }
    
    /**
     * Test add flash error.
     */
    public function testAddFlashError()
    {
        $flash = 'Failed to do something interesting';
        
        $this->app->addFlash( $flash );
        
        $this->assertEquals( 1, count( $this->app->flash[ 'errors' ] ) );
        
        $this->assertEquals( $flash, $this->app->flash[ 'errors' ][ 0 ] );
    }
    
    /**
     * Test add flash success message.
     */
    public function testAddFlashMessage()
    {
        $flash = 'That interesting thing we were waiting for, happened!';
        
        $this->app->addFlash( $flash, 'success' );
        
        $this->assertEquals( 1, count( $this->app->flash[ 'success' ] ) );
        
        $this->assertEquals( $flash, $this->app->flash[ 'success' ][ 0 ] );
    }
    
    /**
     * Test get flash errors.
     */
    public function testGetErrors()
    {
        $this->assertEquals( 0, count( $this->app->getErrors() ) );
        
        $this->app->addFlash( 'Problem 1' );
        $this->app->addFlash( 'Problem 2' );
        
        $errors = $this->app->getErrors();
        
        $this->assertEquals( 2, count( $errors ) );
    }
    
    /**
     * Test get flash success messages.
     */
    public function testGetMessages()
    {
        $this->assertEquals( 0, count( $this->app->getMessages() ) );
        
        $this->app->addFlash( 'Success 1', 'success' );
        $this->app->addFlash( 'Success 2', 'success' );
        
        $messages = $this->app->getMessages();
        
        $this->assertEquals( 2, count( $messages ) );
    }
}