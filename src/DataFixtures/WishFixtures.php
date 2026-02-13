<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $wishesData = [

            [
                'title' => 'Voir les aurores boréales',
                'user' => 'Lucas Martin',
                'description' => 'Observer les aurores boréales en Islande ou en Norvège, loin de toute pollution lumineuse.',
                'created' => '-5 months',
                'updated' => '-4 months',
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Apprendre à jouer du piano',
                'user' => 'Camille Dupont',
                'description' => 'Apprendre le piano et ressentir le plaisir de créer une mélodie.',
                'created' => '-3 months',
                'updated' => null,
                'published' => true,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Faire un road trip aux États-Unis',
                'user' => 'Thomas Leroy',
                'description' => 'Traverser les États-Unis et découvrir ses paysages mythiques.',
                'created' => '-6 months',
                'updated' => '-2 months',
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Courir un marathon',
                'user' => 'Sophie Bernard',
                'description' => 'S’entraîner plusieurs mois pour courir un marathon complet.',
                'created' => '-4 months',
                'updated' => null,
                'published' => false,
                'category' => CategoryFixtures::SPORTS,
            ],
            [
                'title' => 'Plonger avec des tortues',
                'user' => 'Nicolas Moreau',
                'description' => 'Plongée sous-marine et nage avec des tortues marines.',
                'created' => '-2 months',
                'updated' => '-1 month',
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Écrire un livre',
                'user' => 'Élodie Petit',
                'description' => 'Écrire un roman ou un recueil de nouvelles.',
                'created' => '-1 month',
                'updated' => null,
                'published' => false,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Faire un saut en parachute',
                'user' => 'Julien Rousseau',
                'description' => 'Saut en parachute et sensations fortes.',
                'created' => '-5 months',
                'updated' => '-3 months',
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Apprendre une nouvelle langue',
                'user' => 'Manon Girard',
                'description' => 'Apprendre une langue étrangère pour voyager.',
                'created' => '-2 months',
                'updated' => null,
                'published' => true,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Faire le tour du Japon',
                'user' => 'Antoine Lefèvre',
                'description' => 'Découvrir Tokyo, Kyoto et la culture japonaise.',
                'created' => '-6 months',
                'updated' => '-5 months',
                'published' => false,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Assister à un concert mythique',
                'user' => 'Laura Fontaine',
                'description' => 'Vivre l’émotion d’un concert live.',
                'created' => '-3 weeks',
                'updated' => null,
                'published' => true,
                'category' => CategoryFixtures::DIVERTISSEMENTS,
            ],
            [
                'title' => 'Faire un safari en Afrique',
                'user' => 'Claire Morel',
                'description' => 'Observer les animaux sauvages en réserve naturelle.',
                'created' => '-4 months',
                'updated' => '-2 months',
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Gravir le Mont Blanc',
                'user' => 'Maxime Laurent',
                'description' => 'Atteindre le sommet du Mont Blanc.',
                'created' => '-5 months',
                'updated' => null,
                'published' => false,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Créer ma propre entreprise',
                'user' => 'Sarah Chevalier',
                'description' => 'Lancer mon projet entrepreneurial.',
                'created' => '-3 months',
                'updated' => '-1 month',
                'published' => true,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Passer une nuit dans le désert',
                'user' => 'Yanis Benali',
                'description' => 'Dormir sous les étoiles en plein désert.',
                'created' => '-2 months',
                'updated' => null,
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Adopter un chien',
                'user' => 'Julie Caron',
                'description' => 'Adopter un chien dans un refuge.',
                'created' => '-6 months',
                'updated' => '-5 months',
                'published' => false,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Apprendre la photographie',
                'user' => 'Bastien Renaud',
                'description' => 'Maîtriser la lumière et la composition.',
                'created' => '-1 month',
                'updated' => null,
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Faire du bénévolat à l’étranger',
                'user' => 'Ines Marchand',
                'description' => 'Mission humanitaire à l’étranger.',
                'created' => '-4 months',
                'updated' => '-3 months',
                'published' => true,
                'category' => CategoryFixtures::RELATIONS,
            ],
            [
                'title' => 'Voir les pyramides d’Égypte',
                'user' => 'Olivier Garnier',
                'description' => 'Visiter les pyramides de Gizeh.',
                'created' => '-5 months',
                'updated' => null,
                'published' => true,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Traverser l’Europe en train',
                'user' => 'Chloe Fabre',
                'description' => 'Voyager à travers les capitales européennes.',
                'created' => '-3 weeks',
                'updated' => null,
                'published' => false,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Participer à un triathlon',
                'user' => 'Adrien Noel',
                'description' => 'Relever un défi sportif complet.',
                'created' => '-2 months',
                'updated' => '-1 week',
                'published' => true,
                'category' => CategoryFixtures::SPORTS,
            ],
        ];

        foreach ($wishesData as $data) {

            $wish = new Wish();

            $wish->setTitle($data['title']);
            $wish->setDescription($data['description']);
            $wish->setDateCreated(new \DateTimeImmutable($data['created']));

            if ($data['updated']) {
                $wish->setDateUpdated(new \DateTimeImmutable($data['updated']));
            }

            $wish->setPublished($data['published']);

            // Catégorie
            $wish->setCategory(
                $this->getReference($data['category'], Category::class)
            );

            // Utilisateur
            $wish->setUser(
                $this->getReference($data['user'], User::class)
            );

            $manager->persist($wish);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
