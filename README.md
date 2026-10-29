# Gestion des prêts

Application Symfony de gestion de groupements : exercices, campagnes (tontines et épargnes), séances avec cotisations et amendes, et prêts avec ou sans intérêt.

## Prérequis

- PHP 8.4 ou plus, avec les extensions `pdo_sqlite`, `intl`, `mbstring`, `zip`, `curl` et `openssl`
- Composer
- Symfony CLI (facultatif)

## Installation

```bash
git clone https://github.com/eyram-jos/gestion-prets.git
cd gestion-prets
git switch develop
composer install
php bin/console eyramjoseph:db:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

La base est un fichier SQLite (`var/data.db`), configuré dans `.env`.

## Lancer l'application

```bash
symfony server:start
```

Sans la Symfony CLI : `php -S 127.0.0.1:8000 -t public`. Puis ouvrir `http://127.0.0.1:8000`.

## Parcours de l'application

1. `/groupements` : liste des groupements, avec le nombre de membres et d'exercices en cours.
2. Fiche d'un groupement : ses exercices en cours, puis clôturés, et la liste de ses membres.
3. Page d'un exercice : ses campagnes, ses prêts et les intérêts à verser dans la caisse sociale.
4. Page d'une campagne : ses membres, puis séance par séance les cotisations, les amendes et les cumuls, avec le montant total recouvré et le total des amendes.

## Commandes personnalisées

| Commande | Rôle |
|---|---|
| `php bin/console eyramjoseph:db:create` | supprime la base si elle existe puis la recrée |
| `php bin/console eyramjoseph:cache:clear` | vide le cache de l'application |

## Organisation du dépôt

- `develop` : version finale du projet.
- `livrable-2/entites` : entités, statuts d'exercice, trait des dates et migration.
- `livrable-2/fixtures` : données de test.
- `livrable-2/listes` : contrôleurs et vues des listes.
- `livrable-2/documentation` : README et configuration de la base.

Le modèle de classes est décrit dans [docs/modelisation.md](docs/modelisation.md).