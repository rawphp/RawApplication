<?php

/**
 * This file is part of Step in Deals application.
 *
 * Copyright (c) 2014 Tom Kaczocha
 *
 * This Source Code is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, you can obtain one at http://mozilla.org/MPL/2.0/.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   RawPHP\RawApplication\Model
 * @author    Tom Kaczohca <tom@crazydev.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://crazydev.org/licenses/mpl.txt MPL
 * @link      http://crazydev.org/
 */

namespace RawPHP\RawApplication\Model;

use DateTime;
use RawPHP\RawSupport\Entity\Contract\IEntity;

/**
 * Class Model
 *
 * @package RawPHP\RawApplication\Model
 */
abstract class Entity implements IEntity
{
    /** @var  string */
    protected $id;
    /** @var  DateTime */
    protected $dateCreated;
    /** @var  DateTime */
    protected $dateUpdated;

    /**
     * Get the ID.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get date created.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Get date updated.
     *
     * @return DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set ID.
     *
     * @param $id
     */
    public function setId( $id )
    {
        $this->id = $id;
    }

    /**
     * Set date created.
     *
     * @param DateTime $date
     */
    public function setDateCreated( DateTime $date )
    {
        $this->dateCreated = $date;
    }

    /**
     * Set date updated.
     *
     * @param DateTime $date
     */
    public function setDateUpdated( DateTime $date )
    {
        $this->dateUpdated = $date;
    }
}