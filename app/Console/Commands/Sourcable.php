<?php

namespace App\Console\Commands;


trait Sourcable
{
    /**
     * @return string
     */
    public abstract function getSource(): string;
}

