# Modélisation du projet gestion-prets

Source : modèle dessiné au tableau en cours (22/10/2026).

## Diagramme de classes

```mermaid
classDiagram
    class Role {
        String nomRole
    }
    class Membre {
        String nom
        String prenom
        String tel
        String adresse
        String login
        String motDePasse
    }
    class Campagne {
        <<abstract>>
        String nom
        DateTime dateDebut
        DateTime dateFin
        Float montant
    }
    class Tontine
    class Epargne
    class ModeCotisation {
        Boolean isLibre
    }
    class Groupement {
        String nom
        String adresse
        String tel
    }

        class Pret {
        Float montant
        Integer dureeEnMois
        DateTime dateDebut
    }

    Campagne <|-- Tontine
    Campagne <|-- Epargne
    Role "1" --> "*" Membre
    Membre "1" --> "*" ModeCotisation
    Campagne "1" --> "*" ModeCotisation
    Groupement "1" --> "*" Campagne
    Groupement "1" --> "*" Membre
    Membre "1" --> "*" Pret
```

## Lecture du modèle

- Un **rôle** peut être attribué à plusieurs membres.
- **Campagne** est une classe abstraite : on crée toujours une `Tontine` ou une `Epargne`, qui héritent de ses champs. Elle remplace la table `Type` du modèle de départ, ce qui permet d'ajouter un nouveau type de campagne en créant simplement une classe.
- **ModeCotisation** relie les membres aux campagnes (un membre participe à plusieurs campagnes, une campagne a plusieurs membres) et indique si la cotisation est libre.
- Une **coopérative** regroupe des membres et des campagnes.

## À confirmer

- Les noms `Tontine` et `Epargne` : noms provisoires.

## Prochaines évolutions prévues
- Décider si les prêts ont des types (héritage), un statut ou un taux d'intérêt, selon ce que demande le professeur.