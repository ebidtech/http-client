<?php

/*
 * This file is a part of the HTTP Client library.
 *
 * (c) 2014 Ebidtech
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EBT\HttpClient\Tests;

use EBT\HttpClient\CreateTrait;

/**
 * CreateTraitObj
 */
class CreateTraitObj
{
    use CreateTrait {
        create as public;
        createNoHost as public;
    }
}
