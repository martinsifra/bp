<?php

namespace App\Components;

interface IForgottenControlFactory
{
    /** @return ForgottenControl */
    function create();
}
