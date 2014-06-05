<?php

namespace App\Components\User;

interface IEntityControlFactory
{
    /** @return EntityControl */
    function create();
}
