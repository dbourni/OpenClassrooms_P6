<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const USER_ADMIN = 'admin@admin.com';

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

            if ($email == 'admin@admin.com') {
                $this->addReference(self::USER_ADMIN, $user);
            }
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // [$password, $email, $roles];
            ['admin', 'admin@admin.com', ['ROLE_ADMIN']],
            ['user', 'user@user.com', ['ROLE_REGISTERED_USER']],
        ];
    }
}
