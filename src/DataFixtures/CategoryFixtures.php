<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const VOYAGES = 'cat_voyages';
    public const SPORTS = 'cat_sports';
    public const DIVERTISSEMENTS = 'cat_divertissements';
    public const RELATIONS = 'cat_relations';
    public const AUTRES = 'cat_autres';

    public function load(ObjectManager $manager): void
    {
        $categories = [
            self::VOYAGES => 'Voyages et Aventures',
            self::SPORTS => 'Sports',
            self::DIVERTISSEMENTS => 'Divertissements',
            self::RELATIONS => 'Relations Humaines',
            self::AUTRES => 'Autres',
        ];

        foreach ($categories as $reference => $name) {
            $category = new Category();
            $category->setName($name);

            $manager->persist($category);

            // 🔥 Très important
            $this->addReference($reference, $category);
        }

        $manager->flush();
    }
}
