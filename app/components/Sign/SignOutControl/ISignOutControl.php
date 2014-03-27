<?php

namespace App\Components;

interface ISignOutControlFactory
{
    /** @return SignOutControl */
    function create();
}
