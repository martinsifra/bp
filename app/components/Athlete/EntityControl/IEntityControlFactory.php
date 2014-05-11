<?php

namespace App\Components\Athlete;

interface IEntityControlFactory
{
    /** @return EntityControl */
    function create();
}
