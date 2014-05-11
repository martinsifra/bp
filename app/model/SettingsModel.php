<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

/**
 * Description of SettingsModel
 *
 * @author Martin Šifra <me@martinsifra.cz>
 */
class SettingsModel
{
    public $grid;


    public function __construct($grid)
    {
        $this->grid = $grid;
    }
}
