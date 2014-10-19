<?php

namespace App\Components\Profile;

use App\Components\BaseControl;

/**
 * @author Martin Šifra <me@martinsifra.cz>
 */
class SignOutControl extends BaseControl
{

	public function handleSignOut()
	{
		$this->presenter->user->logout();
		$this->presenter->flashMessage('profile.flash.signOut', 'success');
		$this->presenter->redirect('Sign:in');
	}

}

interface ISignOutControlFactory
{

	/** @return SignOutControl */
	function create();
}
