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
 * @package   RawPHP\RawApplication\Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawApplication\Tests;

use PHPUnit_Framework_TestCase;
use RawPHP\RawApplication\Contract\IApplication;
use RawPHP\RawApplication\Support\TestApp;

/**
 * The application tests.
 *
 * @category  PHP
 * @package   RawPHP\RawApplication\Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /** @var  IApplication */
    protected $app;
    /** @var  array */
    protected static $config;

    /**
     * Setup before test suite run.
     */
    public static function setUpBeforeClass()
    {
        global $config;

        parent::setUpBeforeClass();

        self::$config = $config;

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
     * @global array $config
     */
    protected function setUp()
    {
        $this->app = new TestApp( self::$config );
    }

    /**
     * Cleanup after each test.
     */
    protected function tearDown()
    {
        $this->app->setFlash( [ 'success' => [ ], 'errors' => [ ] ] );

        if ( file_exists( OUTPUT_DIR . 'session.json' ) )
        {
            unlink( OUTPUT_DIR . 'session.json' );
        }

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
     * Test that default services have been registered.
     */
    public function testDefaultsRegistered()
    {
        $this->assertInstanceOf( 'RawPHP\RawDispatcher\Contract\IDispatcher', $this->app[ 'dispatcher' ] );
        $this->assertInstanceOf( 'RawPHP\RawFileSystem\Contract\IFileSystem', $this->app[ 'files' ] );
    }

    /**
     * Test database setup correctly.
     */
    public function testDatabaseSetup()
    {
        $this->assertInstanceOf( 'RawPHP\RawDatabase\Contract\IDatabase', $this->app[ 'db' ] );
    }

    /**
     * Test mail setup correctly.
     */
    public function testMailSetup()
    {
        $this->assertInstanceOf( 'RawPHP\RawMail\Contract\IMail', $this->app[ 'mail' ] );
    }

    /**
     * Test log setup correctly.
     */
    public function testLogSetup()
    {
        $this->assertInstanceOf( 'RawPHP\RawLog\Contract\ILog', $this->app[ 'log' ] );
    }

    /**
     * Test request setup correctly.
     *
     * @global array self::$config configuration array
     */
    public function testRequestSet()
    {
        $this->assertNotNull( $this->app[ 'request' ] );

        $this->assertEquals( self::$config[ 'request' ][ 'class' ], get_class( $this->app[ 'request' ] ) );
    }

    /**
     * Test router setup correctly.
     *
     * @global array self::$config configuration array
     */
    public function testRouterSet()
    {
        $this->assertNotNull( $this->app[ 'router' ] );

        $this->assertEquals( self::$config[ 'router' ][ 'class' ], get_class( $this->app[ 'router' ] ) );
        $this->assertEquals( self::$config[ 'router' ][ 'default_controller' ], $this->app[ 'router' ]->getDefaultController() );
        $this->assertEquals( self::$config[ 'router' ][ 'default_action' ], $this->app[ 'router' ]->getDefaultAction() );
        $this->assertCount( 1, $this->app[ 'router' ]->getNamespaces() );
        $this->assertNotNull( $this->app[ 'router' ]->getDispatcher() );
    }

    /**
     * Test session setup correctly.
     *
     * @global array self::$config configuration array
     */
    public function testSessionSet()
    {
        $this->assertNotNull( $this->app[ 'session' ] );

        $this->assertEquals( self::$config[ 'session' ][ 'class' ], get_class( $this->app[ 'session' ] ) );
        $this->assertInstanceOf( 'RawPHP\RawSession\Handler\FileHandler', $this->app[ 'session' ]->getHandler() );
    }

    /**
     * Test application name set.
     *
     * @global array self::$config configuration array
     */
    public function testApplicationNameSet()
    {
        $this->assertEquals( self::$config[ 'app' ][ 'name' ], $this->app->getAppName() );
    }

    /**
     * Test create url.
     */
    public function testCreateUrl()
    {
        $route    = 'home';
        $expected = 'home';

        $result = $this->app->createUrl( $route );

        $this->assertEquals( $expected, $result );
    }

    /**
     * Test create absolute url.
     */
    public function testCreateAbsoluteUrl()
    {
        $route    = 'home';
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

        $this->assertEquals( 1, count( $this->app->getErrors() ) );

        $this->assertEquals( $flash, $this->app->getErrors()[ 0 ] );
    }

    /**
     * Test add flash success message.
     */
    public function testAddFlashMessage()
    {
        $flash = 'That interesting thing we were waiting for, happened!';

        $this->app->addFlash( $flash, 'success' );

        $this->assertEquals( 1, count( $this->app->getFlash()[ 'success' ] ) );

        $this->assertEquals( $flash, $this->app->getFlash()[ 'success' ][ 0 ] );
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