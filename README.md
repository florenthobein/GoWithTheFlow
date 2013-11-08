GoWithTheFlow
=============

Thème wordpress du site GoWithTheFlow.fr

# REFACTORING

	> Sauvegarder les metadonnées d'articles (carte & background) en JS et non dans le DOM
	> Utiliser un objet différé au lieu du flag de traitement d'article (et en profiter pour clarifier le $.when du chargement initial)

# OPTIMISATION

	> Résoudre l'objet différé de fin de chargement d'un article après le préchargement de l'image de fond
	> Précharger les images de galerie des articles
	> Inclure le préchargement de la map dans le preloader initial

# AMELIORATIONS

	> Création d'un tracé de parcours (SVG) par catégorie activable en backoffice

# DEBUG

	> Le drag&drop des articles en mode édition est approximatif