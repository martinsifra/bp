<?php

namespace App\Components\Session;

interface IEntityControlFactory
{
    /** @return EntityControl */
    function create();
}
