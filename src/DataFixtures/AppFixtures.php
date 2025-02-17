<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager ): void
    {

        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setPassword('
        ');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user ,
                'admin1234'

            )
        );

        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

    }
}
