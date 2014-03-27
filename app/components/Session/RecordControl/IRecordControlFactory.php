<?php

namespace App\Components\Session;

interface IRecordControlFactory
{
    /** @return RecordControl */
    function create();
}
