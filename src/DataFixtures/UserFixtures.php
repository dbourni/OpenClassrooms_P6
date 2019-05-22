<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const USER_DBOURNI = 'david@bournisien.net';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$password, $email, $roles]) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $manager->persist($user);

            if ($email == 'david@bournisien.net') {
                $this->addReference(self::USER_DBOURNI, $user);
            }
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$password, $email, $roles];
            ['evdbevdb', 'david@bournisien.net', ['ROLE_ADMIN']],
            ['admin', 'admin@admin.com', ['ROLE_ADMIN']],
            ['user', 'user@user.com', ['ROLE_REGISTERED_USER']],
        ];
    }
}
