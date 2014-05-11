<?php

namespace App\Components\Record;

interface IEntityControlFactory
{
    /** @return EntityControl */
    function create();
}
