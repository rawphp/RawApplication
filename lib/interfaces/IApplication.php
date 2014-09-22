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
 * PHP version 5.3
 * 
 * @category  PHP
 * @package   RawPHP/RawApplication
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawApplication;

/**
 * The application interface.
 * 
 * @category  PHP
 * @package   RawPHP/Core
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IApplication
{
    /**
     * Initialises the application.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_APP_INIT_ACTION
     * @action ON_AFTER_APP_INIT_ACTION
     */
    public function init( $config = NULL );
    
    /**
     * Initialises the application request process.
     * 
     * @action ON_BEFORE_RUN_ACTION
     * @action ON_AFTER_RUN_ACTION
     */
    public function run();
    
    /**
     * Creates a new url.
     * 
     * @param string $route    controller/method route
     * @param array  $params   list of parameters
     * @param bool   $absolute whether it should be an absolute url
     * 
     * @return string the url
     */
    public function createUrl( $route, $params = array(), $absolute = FALSE );
    
    /**
     * Adds a flash message to appear on the next page.
     * 
     * Error   => 'error' ( default )
     * Success => 'success'
     * 
     * @param string $message success or error message
     * @param string $type    the type of message
     */
    public function addFlash( $message, $type = 'error' );
    
    /**
     * Returns a list of error flash messages.
     * 
     * @filter ON_GET_ERRORS_FILTER
     * 
     * @return array list of error messages
     */
    public function getErrors( );
    
    /**
     * Returns a list of success flash messages.
     * 
     * @filter ON_GET_MESSAGES_FILTER
     * 
     * @return array list of success messages
     */
    public function getMessages( );
    
    /**
     * This function outputs caught errors and exceptions.
     * 
     * This method can be overriden in a subclass to change the
     * error output format.
     * 
     * @param array $error the error array
     */
    public static function displayError( $error );
    
    /**
     * Removes old flash messages.
     */
    public function cleanFlash( );
}