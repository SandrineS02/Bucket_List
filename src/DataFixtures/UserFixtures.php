<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        // injecte le service pour hasher le mot de passe
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {

        // crée l'admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@test.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->userPasswordHasher->hashPassword($admin, 'admin')
        );

        $manager->persist($admin);
        // permet à un autre fichier de récupérer l'admin (ici c'est le fichier WishFixtures qui va récupérer ces données)
        $this->addReference('user_admin', $admin);

        // liste des utilisateurs déjà déterminés
        $users = [
            'Lucas Martin',
            'Camille Dupont',
            'Thomas Leroy',
            'Sophie Bernard',
            'Nicolas Moreau',
            'Élodie Petit',
            'Julien Rousseau',
            'Manon Girard',
            'Antoine Lefèvre',
            'Laura Fontaine',
            'Claire Morel',
            'Maxime Laurent',
            'Sarah Chevalier',
            'Yanis Benali',
            'Julie Caron',
            'Bastien Renaud',
            'Ines Marchand',
            'Olivier Garnier',
            'Chloe Fabre',
            'Adrien Noel',
        ];

        foreach ($users as $index => $fullName) {
            $user = new User();

            // username sans espace (ex: lucas.martin)
            $username = strtolower(str_replace(' ', '.', $fullName));

            $user->setUsername($username);
            $user->setEmail($username . '@test.fr');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, '123456')
            );

            $manager->persist($user);

            // Référence basée sur le nom exact utilisé dans WishFixtures
            $this->addReference($fullName, $user);
        }

        $manager->flush();
    }
}
