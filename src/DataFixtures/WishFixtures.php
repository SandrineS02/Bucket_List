<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
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
                'author' => 'Lucas Martin',
                'description' => 'Observer les aurores boréales en Islande ou en Norvège, loin de toute pollution lumineuse, et vivre ce moment magique sous un ciel illuminé de couleurs vertes et violettes.',
                'created' => '-5 months',
                'updated' => '-4 months',
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Apprendre à jouer du piano',
                'author' => 'Camille Dupont',
                'description' => 'Prendre le temps d’apprendre le piano, comprendre la musique, jouer des morceaux simples puis complexes, et ressentir le plaisir de créer une mélodie de ses propres mains.',
                'created' => '-3 months',
                'updated' => null,
                'published' => 1,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Faire un road trip aux États-Unis',
                'author' => 'Thomas Leroy',
                'description' => 'Traverser les États-Unis en voiture, de la côte Est à la côte Ouest, en passant par des parcs nationaux, des villes mythiques et des routes légendaires comme la Route 66.',
                'created' => '-6 months',
                'updated' => '-2 months',
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Courir un marathon',
                'author' => 'Sophie Bernard',
                'description' => 'S’entraîner pendant plusieurs mois afin de courir un marathon complet, dépasser ses limites physiques et mentales, et franchir la ligne d’arrivée avec fierté.',
                'created' => '-4 months',
                'updated' => null,
                'published' => 0,
                'category' => CategoryFixtures::SPORTS,
            ],
            [
                'title' => 'Plonger avec des tortues',
                'author' => 'Nicolas Moreau',
                'description' => 'Faire de la plongée sous-marine dans une eau turquoise et nager aux côtés de tortues marines, tout en respectant la faune et la flore aquatiques.',
                'created' => '-2 months',
                'updated' => '-1 month',
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Écrire un livre',
                'author' => 'Élodie Petit',
                'description' => 'Écrire un livre du début à la fin, qu’il s’agisse d’un roman, d’un recueil de nouvelles ou d’une autobiographie, et aller au bout de ce projet personnel.',
                'created' => '-1 month',
                'updated' => null,
                'published' => 0,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Faire un saut en parachute',
                'author' => 'Julien Rousseau',
                'description' => 'Sauter en parachute depuis plusieurs milliers de mètres d’altitude, ressentir l’adrénaline de la chute libre et admirer la vue avant l’ouverture du parachute.',
                'created' => '-5 months',
                'updated' => '-3 months',
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Apprendre une nouvelle langue',
                'author' => 'Manon Girard',
                'description' => 'Apprendre une nouvelle langue étrangère afin de pouvoir voyager plus facilement, comprendre une autre culture et communiquer avec des personnes du monde entier.',
                'created' => '-2 months',
                'updated' => null,
                'published' => 1,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Faire le tour du Japon',
                'author' => 'Antoine Lefèvre',
                'description' => 'Découvrir le Japon en profondeur, entre traditions et modernité, visiter Tokyo, Kyoto, Osaka, goûter la cuisine locale et comprendre la culture japonaise.',
                'created' => '-6 months',
                'updated' => '-5 months',
                'published' => 0,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Assister à un concert mythique',
                'author' => 'Laura Fontaine',
                'description' => 'Assister à un concert d’un artiste ou d’un groupe emblématique, vivre l’émotion de la musique en live et partager ce moment avec des milliers de personnes.',
                'created' => '-3 weeks',
                'updated' => null,
                'published' => 1,
                'category' => CategoryFixtures::DIVERTISSEMENTS ,
            ],
            [
                'title' => 'Faire un safari en Afrique',
                'author' => 'Claire Morel',
                'description' => 'Partir en safari dans une réserve naturelle en Afrique pour observer les animaux sauvages dans leur habitat naturel et découvrir la richesse de la faune africaine.',
                'created' => '-4 months',
                'updated' => '-2 months',
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Gravir le Mont Blanc',
                'author' => 'Maxime Laurent',
                'description' => 'Se préparer physiquement et mentalement pour gravir le Mont Blanc, atteindre le sommet et profiter d’une vue exceptionnelle sur les Alpes.',
                'created' => '-5 months',
                'updated' => null,
                'published' => 0,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Créer ma propre entreprise',
                'author' => 'Sarah Chevalier',
                'description' => 'Lancer mon propre projet entrepreneurial, développer une idée qui me passionne et construire une activité durable et indépendante.',
                'created' => '-3 months',
                'updated' => '-1 month',
                'published' => 1,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Passer une nuit dans le désert',
                'author' => 'Yanis Benali',
                'description' => 'Dormir sous une tente en plein désert, admirer un ciel étoilé d’une pureté incroyable et vivre une expérience dépaysante loin de toute agitation.',
                'created' => '-2 months',
                'updated' => null,
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Adopter un chien',
                'author' => 'Julie Caron',
                'description' => 'Adopter un chien dans un refuge afin de lui offrir une nouvelle vie, partager des moments de complicité et apprendre la responsabilité au quotidien.',
                'created' => '-6 months',
                'updated' => '-5 months',
                'published' => 0,
                'category' => CategoryFixtures::AUTRES,
            ],
            [
                'title' => 'Apprendre la photographie',
                'author' => 'Bastien Renaud',
                'description' => 'Maîtriser les bases de la photographie, comprendre la lumière et la composition, et capturer des moments uniques à travers l’objectif.',
                'created' => '-1 month',
                'updated' => null,
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Faire du bénévolat à l’étranger',
                'author' => 'Inès Marchand',
                'description' => 'Participer à une mission humanitaire à l’étranger pour aider une communauté locale tout en découvrant une nouvelle culture.',
                'created' => '-4 months',
                'updated' => '-3 months',
                'published' => 1,
                'category' => CategoryFixtures::RELATIONS,
            ],
            [
                'title' => 'Voir les pyramides d’Égypte',
                'author' => 'Olivier Garnier',
                'description' => 'Voyager en Égypte pour admirer les pyramides de Gizeh, en apprendre davantage sur l’histoire des pharaons et explorer les trésors antiques.',
                'created' => '-5 months',
                'updated' => null,
                'published' => 1,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Traverser l’Europe en train',
                'author' => 'Chloé Fabre',
                'description' => 'Organiser un voyage en train à travers plusieurs capitales européennes afin de découvrir différentes cultures, cuisines et architectures.',
                'created' => '-3 weeks',
                'updated' => null,
                'published' => 0,
                'category' => CategoryFixtures::VOYAGES,
            ],
            [
                'title' => 'Participer à un triathlon',
                'author' => 'Adrien Noel',
                'description' => 'S’entraîner pour participer à un triathlon combinant natation, vélo et course à pied, et relever ce défi sportif complet.',
                'created' => '-2 months',
                'updated' => '-1 week',
                'published' => 1,
                'category' => CategoryFixtures::SPORTS,
            ],

        ];

        foreach ($wishesData as $data) {
            $wish = new Wish();
            $wish->setTitle($data['title']);
            $wish->setAuthor($data['author']);
            $wish->setDescription($data['description']);
            $wish->setDateCreated(new \DateTimeImmutable($data['created']));

            if ($data['updated']) {
                $wish->setDateUpdated(new \DateTimeImmutable($data['updated']));
            }

            $wish->setPublished($data['published']);

            // on récupère la catégorie
            $wish->setCategory(
                $this->getReference($data['category'], Category::class)
            );



            $manager->persist($wish);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
