<?php

namespace App\Components\Coach;

interface IEntityControlFactory
{
    /** @return EntityControl */
    function create();
}
