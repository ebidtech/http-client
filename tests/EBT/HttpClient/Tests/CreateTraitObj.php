<?php

/**
 * LICENSE: [EMAILBIDDING_DESCRIPTION_LICENSE_HERE]
 *
 * @author     Eduardo Oliveira <eduardo.oliveira@adclick.pt>
 * @copyright  2012-2013 Emailbidding
 * @license    [EMAILBIDDING_URL_LICENSE_HERE]
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
