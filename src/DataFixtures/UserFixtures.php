<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USERS = [
        [
            'email' => 'g.hartemann@gmail.com',
            'password' => 'password',
            'roles' => ['ROLE_ADMIN', 'ROLE_USER'],
            'mate' => 'aurianephd@gmail.com',
        ],
        [
            'email' => 'aurianephd@gmail.com',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
            'mate' => 'g.hartemann@gmail.com',
        ],

    ];

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userInput) {
            $user = new User();

            $hashedPassword = $this->passwordHasher->hashPassword($user, $userInput['password']);

            $user
                ->setEmail($userInput['email'])
                ->setPassword($hashedPassword)
                ->setRoles($userInput['roles'])
                ->setMate($userInput['mate']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
