<?php

namespace App\Forms\Rendering;

/**
 * @author Martin Šifra <me@martinsifra.cz>
 */
class Bs3FormRenderer extends \Nextras\Forms\Rendering\Bs3FormRenderer
{
	public function __construct()
	{
		parent::__construct();
		$this->wrappers['error']['container'] = NULL;
		$this->wrappers['error']['item'] = 'div class="alert alert-danger"';
	}
}