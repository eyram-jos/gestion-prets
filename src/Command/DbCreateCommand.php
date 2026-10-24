<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'eyramjoseph:dbcreate',
    description: 'Supprime la base de données si elle existe puis la recrée',
)]
class DbCreateCommand extends Command
{
    // Symfony nous « donne » automatiquement la connexion à la base.
    public function __construct(private Connection $connection)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // On récupère les réglages de la connexion (type de base, chemin du fichier...).
        $params = $this->connection->getParams();

        $io->writeln('La commande eyramjoseph:dbcreate est reconnue.');
        $io->writeln('Type de base : ' . $params['driver']);

        return Command::SUCCESS;
    }
}