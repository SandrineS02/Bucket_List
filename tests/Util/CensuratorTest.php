<?php

namespace App\Tests\Util;

use App\Util\Censurator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CensuratorTest extends KernelTestCase
{
    public function testCensuratorIsValid(): void
    {
        self::bootKernel();
        // On récupère le service Censurator dans le container
        $censurator = static::getContainer()->get(Censurator::class);

        // On teste que tous les mots interdits sont bien remplacés par des *
        $this->assertSame('Je suis au ******', $censurator->purify('Je suis au bordel'));
        $this->assertSame('C\'est une pub sur le ******', $censurator->purify('C\'est une pub sur le viagra'));
        $this->assertSame('Je suis ***', $censurator->purify('Je suis con'));
        $this->assertSame('J\'aime la ****** split', $censurator->purify('J\'aime la banana split'));
        // On teste que chaque mot interdit est bien remplacé par des *
        // autant de fois qu'il y en a dans la même chaîne de caractères
        $this->assertSame('Je suis au ******. J\ai perdu au ******.', $censurator->purify('Je suis au casino. J\ai perdu au casino.'));
    }
}