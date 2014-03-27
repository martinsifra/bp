<?php

namespace App\Components\User;

interface IGridControlFactory
{
    /** @return GridControl */
    function create();
}
