<?php

namespace App\Command;

use App\Repository\WishRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:purge-wish',
    description: 'Suppression des souhaits non publiés et créés depuis plus de 6 mois',
)]
class PurgeWishCommand extends Command
{
    public function __construct(
        private readonly WishRepository $wishRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('months', null, InputOption::VALUE_REQUIRED, 'Nombre de mois')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            // on récupère la valeur de l'option "months"
            $nbMonths = (int)$input->getOption('months');
            if ($nbMonths <= 0) {
                // Si la valeur est incorrecte, on repose la question à l'utilisateur.
                // L'utilisateur aura 2 tentatives possibles.
                $helper = $this->getHelper('question');
                $question = new Question('Saisir le nombre de mois : [6]', "6");
                $question->setValidator(function (string $answer): string {
                    if (intval($answer) <= 0) {
                        throw new \RuntimeException('Valeur incorrecte');
                    }
                    return $answer;
                });
                $question->setMaxAttempts(2);
                $nbMonths = (int)$helper->ask($input, $output, $question);
            }
            $this->wishRepository->purge($nbMonths);
            $io->success('Traitement terminé avec succès.');
            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $io->error('Le traitement a échoué \n'.$ex->getMessage());
            return Command::FAILURE;
        }
    }
}