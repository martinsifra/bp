<?php

namespace App\Components;

interface ISignInControlFactory
{
    /** @return SignInControl */
    function create();
}
