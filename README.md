test-dev
========



Pratique : 
1. Revoir complètement la conception du code (découper le code afin de pouvoir ajouter de nouveaux flux simplement) :

Ma proposition est d'ajouter un service HomeService qui va  consommer le flux rss et l'api New Api avec le httpclient 
puis formater le contenu de la réponse du flux rss en  SimpleXMLElement afin de récupérer les urls et  en json  pour récupérer les urls des images du New Api
Puis dans HomeController on va utiliser ce service pour afficher les images dans le homepage et à l'aide du bundle knp-paginator-bundle on affiche chaque image dans une page


Questions théoriques : 
1. Que mettriez-vous en place afin d'améliorer les temps de réponses du script:

Mettre à jour la version php du projet de la version 7.2.5  ou moins vers php 7.4 pour bénéficier du Pré chargement (preloading) OPcache. Et Activer le Pré chargement dans nos services pour pré charger autant des classes que nécessaire
Le pré chargement de classe PHP est l’une des fonctionnalités les plus importantes ajoutées dans PHP 7.4. Au démarrage du serveur avant que tout code d’application ne soit exécuté .PHP peut charger un certain ensemble de fichiers PHP en mémoire et rendre leur contenu disponible en permanence pour toutes les requêtes ultérieures.
Dans Symfony 5 le pré chargement est plus facile à configurer grâce à deux nouvelles balises d’injection de dépendances appelées :
  .container.preload  container.no_preload
Donc Il faut générer un fichier de pré chargement pour notre application dans le répertoire cache.  Le nom de fichier généré inclut à la fois les noms de l’environnement et du noyau (par exemple).var/cache/dev/App_KernelDevDebugContainer.preload.php
Utiliser  ce fichier généré comme valeur de la directive PHP :opcache.preload
Dans php.ini :      opcache.preload=/path/to/project/var/cache/prod/App_KernelProdContainer.preload.php


2. Comment aborderiez-vous le fait de rendre scalable le script (plusieurs milliers de sources et images):

Pour rendre  le script  scalable on peut utiliser le design pattern Adapter on crée une interface FluxRssAdapterInterface et un class FluxRssAdapter pour formater le contenu des flux RSS selon une structure bien défini, s’il y a une spécification dans un flux on peut développer une fonction qui  traite cette spécification
Si la source est une Api on peut utiliser aussi  le même principe  du design pattern Adapter pour formater la réponse  en json si ce n’est pas le cas et ajouter   le bundel jmespath.php pour chercher l’url des images dans l’api puisque les api  n’ont pas la même structure 

