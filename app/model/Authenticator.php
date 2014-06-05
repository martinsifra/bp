<?php

namespace App;

use Nette,
	Nette\Utils\Strings;


/**
 * Users management.
 */
class Authenticator implements Nette\Security\IAuthenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role';

    /** @var \Kdyby\Doctrine\EntityDao */
    private $dao;

    /** @var \App\Model\UserModel */
    private $users;

	public function __construct(\Kdyby\Doctrine\EntityDao $dao, \App\Model\UserModel $userModel)
	{
        $this->dao = $dao;
        $this->users = $userModel;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

        $row = new \App\Entities\User();
        $row = $this->dao->findOneBy(['username' => $username]);
//                ->createQueryBuilder('u')
//                ->select('u', 'r')
//                ->where ('u.username = :username')
//                ->join('u.roles','r')
//                ->setParameter(':username', $username)
//                ->getQuery()
//                ->getSingleResult();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!Passwords::verify($password, $row->password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($row->password)) {
			$row->password = Passwords::hash($password);
            $this->dao->save($row);
		}

        $roles = [];
        $rolesLabeled = [];
        foreach ($row->roles as $role) {
            $roles[$role->id] = $role->name;
            $rolesLabeled[$role->id] = [
                'name' => $role->name,
                'label' => $role->label
            ];
        }

        $data = [
            'username' => $row->username,
            'firstname' => $row->firstname,
            'surname' => $row->surname,
            'roles' => $rolesLabeled
        ];
        
        if ($row->athlete) {
            $data['athlete'] = $row->athlete->id;
        }
        
        if ($row->coach) {
            $data['coach'] = $row->coach->id;
        }
        
		return new Nette\Security\Identity($row->id, $roles, $data);
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($username, $password)
	{
		$this->database->table(self::TABLE_NAME)->insert(array(
			self::COLUMN_NAME => $username,
			self::COLUMN_PASSWORD_HASH => Passwords::hash(self::removeCapsLock($password)),
		));
	}


	/**
	 * Fixes caps lock accidentally turned on.
	 * @return string
	 */
	private static function removeCapsLock($password)
	{
		return $password === Strings::upper($password)
			? Strings::lower($password)
			: $password;
	}

}
