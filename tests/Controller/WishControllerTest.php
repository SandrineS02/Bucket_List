<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WishControllerTest extends WebTestCase
{
    // fonction qui teste que si l'utilisateur n'est pas connecté,
    // dans ce cas, il ne peut pas accéder à la page de création d'un souhait
    public function testAccessDeniedIfUserNotConnected(): void
    {
        // crée un client HTTP pour simuler un navigateur
        $client = static::createClient();

        //essaie d'accéder à la page de création d'un souhait
        $client->request('GET', '/creer');

        //vérifie que la réponse est une redirection (302)
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        //suit la redirection pour arriver sur la page de connexion
        $client->followRedirect();

        // vérifie que la route actuelle est celle de la page de connexion(login)
        $this->assertRouteSame('app_login');
    }

    //fonction qui teste  si l'utilisateur peut accéder à la page de création quand il est connecté,
    public function testAccessAuthorizedIfUserConnected(): void
    {
        $client = static::createClient();

        //récupère le UserRepository
        $userRepository = static::getContainer()->get(UserRepository::class);

        //récupère un utilisateur (ici c'est l'admin)
        $user = $userRepository->findOneBy(['email' => 'admin@test.fr']);

        //simule sa connexion
        $client->loginUser($user);

        //demande la page de création d'un souhait
        $client->request('GET', '/creer');

        //vérifie que la réponse est un succès (200)
        $this->assertResponseIsSuccessful();

        //vérifie qu'il y a un titre h1 dans la page
        $this->assertSelectorTextContains('h2', 'Créer un souhait');
    }

    // récupére un utilisateur
    private function getUser(): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        return $userRepository->findOneBy(['email' => 'admin@test.fr']);
    }

    // teste que le formulaire soit bien invalide quand aucune catégorie n'est choisie
    public function testFormIsInvalidIfNoCategory(): void
    {
        $client = static::createClient();

        // on se connecte avec notre utilisateur(admin)
        $client->loginUser($this->getUser());

        //accède à la page de création
        $crawler = $client->request('GET', '/creer');

        //imule la soumission du formulaire
        $client->submitForm("Créer", [
            'wish_form[title]' => 'Test',
            'wish_form[description]' => 'Description test',
            'wish_form[category]' => '' // catégorie vide
        ]);

        // vérifie que l'on a bien une réponse erreur 422
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    // teste si le formulaire est invalide quand le titre est trop petit
    public function testFormIsInvalidIfTitleHaveTooSmallLength(): void
    {
        $client = static::createClient();
        $client->loginUser($this->getUser());
        $crawler = $client->request('GET', '/creer');

        $client->submitForm("Créer", [
            'wish_form[title]' => 'x', // trop court
            'wish_form[description]' => 'Description test',
            'wish_form[category]' => '1' // valeur valide
        ]);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    // teste que le formulaire est invalide quand le titre est trop long
    public function testFormIsInvalidIfTitleHaveTooBigLength(): void
    {
        $client = static::createClient();
        $client->loginUser($this->getUser());
        $crawler = $client->request('GET', '/creer');

        // On crée un titre de 256 caractères (trop long)
        $title = str_repeat('x', 256);

        $client->submitForm("Créer", [
            'wish_form[title]' => $title,
            'wish_form[description]' => 'Description test',
            'wish_form[category]' => '1' // valeur valide
        ]);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    // teste que le formulaire est invalide quand la description est trop courte
    public function testFormIsInvalidIfDescriptionHaveTooSmallLength(): void
    {
        $client = static::createClient();
        $client->loginUser($this->getUser());
        $crawler = $client->request('GET', '/creer');

        $client->submitForm("Créer", [
            'wish_form[title]' => 'Test',
            'wish_form[description]' => 'x', // 1 seule lettre = trop court
            'wish_form[category]' => '1' // valeur valide
        ]);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    // teste que le formulaire est invalide quand la description est trop longue
    public function testFormIsInvalidIfDescriptionHaveTooBigLength(): void
    {
        $client = static::createClient();
        $client->loginUser($this->getUser());
        $crawler = $client->request('GET', '/creer');

        //crée une description de 5001 caractères (trop long)
        $description = str_repeat('x', 5001);

        $client->submitForm("Créer", [
            'wish_form[title]' => 'Test',
            'wish_form[description]' => $description,
            'wish_form[category]' => '1' // valeur valide
        ]);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }
}
