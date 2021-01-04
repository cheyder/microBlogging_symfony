<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    private const USERS = [
        [
            'username' => 'karla_uwo',
            'email' => 'karla@uwo.de',
            'password' => 'karlawillkekse',
            'fullName' => 'Karla Uwo',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'username' => 'ingo_bingo',
            'email' => 'ingo@bingo.de',
            'password' => 'ingowillinge',
            'fullName' => 'Ingo Bingo',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'inge_lingeling',
            'email' => 'inge@lingeling.de',
            'password' => 'ingewillingo',
            'fullName' => 'Inge Lingeling',
            'roles' => [User::ROLE_USER]
        ]
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                self::POST_TEXT[random_int(0, count(self::POST_TEXT) - 1)]
            );

            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $microPost->setTime($date);

            $microPost->setUser($this->getReference(
                self::USERS[random_int(0, count(self::USERS) - 1)]['username']
            ));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userData['password']
            ));
            $user->setRoles($userData['roles']);
            $this->addReference($userData['username'], $user);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
