<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // user 1
        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );

        $user
            ->setEmail("g.hartemann@gmail.com")
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setPassword($hashedPassword);
        $manager->persist($user);

        // user 2
        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );

        $user
            ->setEmail('aurianephd@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
