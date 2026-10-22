# Migration de LaraLight vers Laravel 12 et PHP 8.5

Sources initiales : `edfa016`. Branche locale : `migration/laravel-12`. Cible déclarée : Laravel `^12.0` et PHP `~8.5.12`.

Ces plugins ne contenaient pas leur propre installation Laravel ni de contrainte de version. Les paliers du framework ont été réalisés dans LaraHome ; ce dépôt porte maintenant ses adaptations et sa suite spécifique pour la cible finale. Aucun nouveau passage autonome par les anciennes versions de Laravel n’est revendiqué ici.

## Modifications

- Cast de `on_since` vers une date et calcul signé du temps écoulé avec Carbon 3.
- Arrêt automatique limité aux lumières expirées ; absence de date gérée sans erreur.
- Validation numérique du niveau entre 0 et 100.
- Vérification des publications MQTT de niveau et de commande forcée et de la mise à jour de l’état.

Les routes utilisent des références de classes explicites et exigent une authentification. Les migrations historiques et les formats de topics MySensors sont conservés.

## Tests

Résultat sous Laravel 12.70.1 et PHP 8.5.12 : **4 tests, 22 assertions**, sur SQLite en mémoire et MariaDB 10.11 isolée.

Les tests utilisent LaraHome comme application hôte, car le plugin dépend de ses classes `App` et de son client MQTT. Ils chargent les sources de ce dépôt en priorité sur les copies de vendor. Les connexions MQTT sont remplacées par des doublures ; aucun équipement réel n’est commandé. Les vérifications portent sur les comportements décrits ci-dessus, sans constituer un audit complet des permissions.

Depuis ce dépôt, avec LaraHome migré dans le dossier voisin :

```bash
docker build -f ../LaraHome/tools/migration/Dockerfile.php85 -t larahome-migration:php85 ../LaraHome
bash tools/test --display-deprecations --display-warnings
```

Installer auparavant les dépendances de LaraHome avec PHP 8.5, suivant son journal de migration. `LARAHOME_PATH` permet de choisir un autre dossier hôte. Le lanceur utilise par défaut un conteneur sans réseau et SQLite en mémoire.

Pour les tests MariaDB, utiliser uniquement une base jetable, sur un réseau Docker interne, puis définir `MIGRATION_NETWORK`, `PLUGIN_TEST_DB_CONNECTION=mysql`, `PLUGIN_TEST_DB_HOST`, `PLUGIN_TEST_DB_DATABASE`, `PLUGIN_TEST_DB_USERNAME` et `PLUGIN_TEST_DB_PASSWORD`. La suite utilise `RefreshDatabase` et peut effacer les tables de la base désignée.

## Avant publication

Les dépendances sont déclarées dans composer.json ; le verrou utilisé pour les tests est celui de l’application LaraHome. Le dépôt de bibliothèque n’embarque pas de vendor ni de verrou propre. Les tests avec équipements réels et les parcours visuels restent à vérifier en préproduction. Aucun push, tag ou déploiement n’a été effectué.
