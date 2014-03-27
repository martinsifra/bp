<?php

namespace App\Components\Session;

interface IGridControlFactory
{
    /** @return GridControl */
    function create();
}
