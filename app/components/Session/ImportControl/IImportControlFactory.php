<?php

namespace App\Components\Session;

interface IImportControlFactory
{
    /** @return ImportControl */
    function create();
}
