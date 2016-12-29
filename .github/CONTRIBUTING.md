# Qu’est-ce qu’une bonne Pull Request (PR) ?

- le titre de la PR est en **français**
- le titre de le PR est au format : label(categorie): titre
- le titre de la PR est à **l’impératif** et en **minuscule** : “fixe un bug” et pas “Bug fixé”
- la description de la PR est en **français**
- le **cadrage/spécification technique** de la PR
- le cahier de **recette** et les succès pour vérifier la PR
- si la PR contient des **dépendances**, elles sont indiquées (via une checklist)

# Les commits

- les messages de commit sont **en anglais**
- les messages de commit suivent le format label(category): title
- les messages de commit sont à **l’impératif** et en **minuscule** : “fix the bug” et pas “Bug fixed”

# Les labels de workflow

La PR a toujours un de ces labels :
- **status/wip** : la PR ne doit pas être mergée. Elle est ouverte aux CR.
- **status/reviewable** : la PR est ouverte aux CR. Elle doit être marquée status/mergeable si elle satisfait 2 CR.
- **status/mergeable** : la PR doit être mergée.
- **status/depend** : la PR indique qu’il y a des dépendances externes (peut dépendre d’une autre PR, d’une personne, etc.)

# Les labels de qualification

La PR a toujours un de ces labels :
- **type/question** : quand on veut l’avis d’un pair pour un cadrage technique ou fonctionnel, ou lors d’un POC.
- **type/feat** : qand la PR a un impact pour les utilisateurs (ajout, suppression ou évolution d’une fonctionnalité).
- **type/fix** : quand la PR corrige un bug.
- **type/chore** : quand la PR n’a pas d’impact utilisateur (ajout de log, bump de release, modification d’outils internes par exemple).
- **type/refactor** : quand la PR ne fait que modifier le code mais n’ajoute pas de fonctionnalité ou ne corrige pas de bug.
- **type/test** : quand la PR ajoute des tests oubliés précédemment.
