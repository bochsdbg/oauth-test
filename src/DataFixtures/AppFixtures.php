<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /** @required */
    public UserPasswordEncoderInterface $encoder;

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('admin')
            ->setPassword($this->encoder->encodePassword($user, '123'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_OAUTH_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }
}
