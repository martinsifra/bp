<?php

namespace App\Components;

interface IRecoveryControlFactory
{
    /** @return RecoveryControl */
    function create();
}
