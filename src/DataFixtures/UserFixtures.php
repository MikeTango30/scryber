<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $roles[] = 'ROLE_USER';

        $user = new User();
        $user->setFirstname("Kirkas");
        $user->setEmail("kapitonas@enterprise.stars");
        $user->setRoles($roles);
        $user->setPassword($this->passwordEncoder->encodePassword($user,'the_new_password'));

        $user2 = new User();
        $user2->setFirstname("Hanas");
        $user2->setEmail("skrendu1@sakalas.wars");
        $user2->setRoles($roles);
        $user2->setPassword($this->passwordEncoder->encodePassword($user,'the_better_new_password'));

        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();
    }
}
