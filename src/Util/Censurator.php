<?php

namespace App\Util;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Censurator
{
    public function __construct(
        private readonly string $fichier)
    {
    }

    public function purify(?string $text): string
    {
        if (file_exists($this->fichier)) {
            // on récupère les mots du fichier sous forme d'un tableau
            //on retire le retour chariot présent en fin de chaque ligne
            $words = file($this->fichier, FILE_IGNORE_NEW_LINES);
            // on remplace le mot par un nombre précis d'*
            foreach($words as $unwantedWord) {
                // autant d'* que de lettres dans le mot :
                $replacement = str_repeat("*", mb_strlen($unwantedWord));
                $text = str_ireplace($unwantedWord, $replacement, $text);
            }
        }
        return $text;
    }
}