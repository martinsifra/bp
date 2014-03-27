<?php

namespace App\Model;

/**
 * Description of UserModel
 *
 * @author Martin
 */
class UserModel extends BaseModel
{
   
    /**
     * Map User entity to array for Authenticator and Nette\Security\Identity.
     * @param string $username
     * @return array
     */
    public function forAuthenticator($username)
    {
        $row = $this->dao->createQueryBuilder('u')
                ->select('u', 'r')
                ->where ('u.username = :username')
                ->join('u.roles','r')
                ->setParameter(':username', $username)
                ->getQuery()
                ->getSingleResult();

        if (!$row) {        
            return NULL;
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

        $user = [
            'id' => $row->id,
            'password' => $row->password,
            'roles' => $roles,
            'data' => [
                'username' => $row->username,
                'roles' => $rolesLabeled                
            ]                
        ];

        return $user;
    }
}
