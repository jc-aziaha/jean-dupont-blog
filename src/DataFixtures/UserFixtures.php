<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }
    
    public function load(ObjectManager $manager): void
    {
        $user1 = $this->createUser1();
        $user2 = $this->createUser2();
        $user3 = $this->createUser3();

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        
        $manager->flush();
    }

    /**
     * Cette méthode permet de créer l'utilisateur 1
     */
    private function createUser1(): User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, 'azerty1234A*');

        $user->setFirstName('Riri');
        $user->setLastName('Duck');
        $user->setEmail('riri@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }


    /**
     * Cette méthode permet de créer l'utilisateur 2
     */
    private function createUser2(): User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, 'azerty1234A*');

        $user->setFirstName('Fifi');
        $user->setLastName('Duck');
        $user->setEmail('fifi@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }

    /**
     * Cette méthode permet de créer l'utilisateur 3
     */
    private function createUser3(): User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, 'azerty1234A*');

        $user->setFirstName('loulou');
        $user->setLastName('Duck');
        $user->setEmail('loulou@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }
}
