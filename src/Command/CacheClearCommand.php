<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'eyramjoseph:cache:clear',
    description: "Vide le cache de l'application",
)]
class CacheClearCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // On retrouve la commande cache:clear de Symfony, puis on la lance.
        $clear = $this->getApplication()->find('cache:clear');
        $code = $clear->run(new ArrayInput([]), $output);
        if (Command::SUCCESS !== $code) {
            $io->error('Échec du vidage du cache.');
            return Command::FAILURE;
        }

        // Message de succès final, affiché dans un cadre vert.
        $io->success('Cache vidé.');

        return Command::SUCCESS;
    }
}