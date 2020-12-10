<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN']);

        $password = $this->encoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();
    }
}
