<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
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
        $application = $this->getApplication();

        // On récupère les réglages de la connexion (type de base, chemin du fichier...).
        $params = $this->connection->getParams();

        // Si la connexion a un « path », c'est une base SQLite (un simple fichier).
        $isSqlite = isset($params['path']);

        $io->writeln('La commande eyramjoseph:dbcreate est reconnue.');
        $io->writeln('Type de base : ' . $params['driver']);
        $io->writeln($isSqlite ? 'Base SQLite détectée (un simple fichier).' : 'Base serveur détectée (MySQL, PostgreSQL...).');

        // Action 1 : supprimer la base si elle existe.
        if ($isSqlite) {
            // SQLite : on ferme la connexion, puis on supprime le fichier.
            $this->connection->close();
            if (file_exists($params['path'])) {
                unlink($params['path']);
                $io->writeln('Base SQLite supprimée : ' . $params['path']);
            } else {
                $io->writeln('Aucune base à supprimer.');
            }
        } else {
            // Autres bases (MySQL, PostgreSQL) : on utilise la commande de Doctrine.
            $drop = $application->find('doctrine:database:drop');
            $code = $drop->run(new ArrayInput(['--if-exists' => true, '--force' => true]), $output);
            if (Command::SUCCESS !== $code) {
                $io->error('Échec de la suppression de la base.');
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}