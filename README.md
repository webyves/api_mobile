# BILEMO API
Projet 7 du parcours DA PHP/Symfony de OpenClassrooms
- lien site hebergé : http://bilemo.ybernier.fr
- lien fichier Git : https://github.com/webyves/api_mobile

# Code Validation
- [![SymfonyInsight](https://insight.symfony.com/projects/8ca6aaf9-d9ae-42b1-a5ec-d1bf37a935b5/big.svg)](https://insight.symfony.com/projects/8ca6aaf9-d9ae-42b1-a5ec-d1bf37a935b5)
- codacy badge
- liens vers analyse Codacy : https://app.codacy.com/project/webyves/snow_tricks/dashboard

# Installation Notes (SANS ACCES SSH)
1) Cloner le repository sur votre serveur
	- dé-zipper le fichier vendor.zip a la racine de votre dossier.
	- dé-zipper le fichier htaccess.zip dans le dossier {VOTRE_DOSSIER_DE_PROJET}/public
2) Importer le fichier SQL de votre choix sur votre base de donnée MySQL :
	- DB_MySQL_Install.sql est une base de donnée vierge
	- DB_MySQL_Demo.sql est une base de donnée avec un jeu de demo
3) faites pointer votre domaine (ou sous-domaine multisite) sur le dossier {VOTRE_DOSSIER_DE_PROJET}/public
4) Mettre a jour le fichier .env (situé a la racine) sur les lignes suivantes :
	- DB_HOST={VOTRE_SERVEUR_DATABASE}
	- DB_NAME={NOM_DE_VOTRE_DATABASE}
	- DB_USER={VOTRE_NOM_UTILISATEUR_DATABASE}
	- DB_PASSWORD={VOTRE_MOT_PASSE_DATABASE}

	- EMAIL_URL={VOTRE_SERVEUR_EMAIL}
	- EMAIL_PORT={VOTRE_PORT_DE_SERVEUR_EMAIL}  465 pour le SSL
	- EMAIL_ENCRYPTION={VOTRE_TYPE_DE_SECURITE_EMAIL}  SSL est le plus repandu
	- EMAIL_MODE={VOTRE_METHODE_CONNEXION_EMAIL}  login est le plus repandu
	- EMAIL_USERNAME={VOTRE_NOM_UTILISATEUR_EMAIL}
	- EMAIL_PASSWORD={VOTRE_MOT_PASSE_EMAIL}

	- ADMIN_CONTACT_EMAIL={EMAIL_DE_VOTRE_ADMINISTRATEUR}

	- CAPTCHA_SITE_KEY={VOTRE_CLEF_SITE_RECAPTCHA}
	- CAPTCHA_SECRET_KEY={VOTRE_CLEF_SECRETE_RECAPTCHA}

	- FB_APP_ID={VOTRE_CLEF_APP_ID_FACEBOOK}
	- FB_APP_SECRET={VOTRE_CLEF_APP_SECRET_FACEBOOK}

# Installation Notes (PAR SSH)
1) Cloner le repository sur votre serveur
2) Mettre a jour le fichier .env (situé a la racine) sur les lignes suivantes :
	- DB_HOST={VOTRE_SERVEUR_DATABASE}
	- DB_NAME={NOM_DE_VOTRE_DATABASE}
	- DB_USER={VOTRE_NOM_UTILISATEUR_DATABASE}
	- DB_PASSWORD={VOTRE_MOT_PASSE_DATABASE}

	- EMAIL_URL={VOTRE_SERVEUR_EMAIL}
	- EMAIL_PORT={VOTRE_PORT_DE_SERVEUR_EMAIL}  465 pour le SSL
	- EMAIL_ENCRYPTION={VOTRE_TYPE_DE_SECURITE_EMAIL}  SSL est le plus repandu
	- EMAIL_MODE={VOTRE_METHODE_CONNEXION_EMAIL}  login est le plus repandu
	- EMAIL_USERNAME={VOTRE_NOM_UTILISATEUR_EMAIL}
	- EMAIL_PASSWORD={VOTRE_MOT_PASSE_EMAIL}

	- ADMIN_CONTACT_EMAIL={EMAIL_DE_VOTRE_ADMINISTRATEUR}

	- CAPTCHA_SITE_KEY={VOTRE_CLEF_SITE_RECAPTCHA}
	- CAPTCHA_SECRET_KEY={VOTRE_CLEF_SECRETE_RECAPTCHA}
	
	- FB_APP_ID={VOTRE_CLEF_APP_ID_FACEBOOK}
	- FB_APP_SECRET={VOTRE_CLEF_APP_SECRET_FACEBOOK}
3) Utiliser composer pour installer et mettre a jour les composant avec la commande 
	- composer install
4) Créer la base de donnée et mettez la a jour avec les commandes
	- php bin/console doctrine:database:create
	- php bin/console doctrine:migrations:migrate


# Patch Notes
BILEMO API V1.0
