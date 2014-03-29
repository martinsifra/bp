<?php

namespace App\Components\Record;

interface IRecordControlFactory
{
    /** @return RecordControl */
    function create();
}
