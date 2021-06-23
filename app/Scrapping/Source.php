<?php

namespace App\Scrapping;

use BenSampo\Enum\Enum;

/**
 * Class Source
 * @package App\Scrapping
 *
 * @method static Source Bookclub()
 * @method static Source Starylev()
 */
final class Source extends Enum
{
    const Bookclub = 'bookclub';
    const Starylev = 'starylev';
}
