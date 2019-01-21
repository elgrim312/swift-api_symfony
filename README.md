# Système d'émargement

## Api réaliser dans le cadre du cours symfony / swift en Web 3 P2018 Hétic

## Création des utilisateurs 

Pour créer de nouveaux **utilisateurs** lancez la commande `php bin/console app:create-user` avec les arguments suivant et dans l'ordre :

 1. myemail@mydomain.com
 2. Myname
 3. Mypassword

## Création d'un event

Pour créer de nouveaux **events** lancez la commande `php bin/console app:create-event` avec les arguments suivant et dans l'ordre :

 1. `location ID`
 2. Event name

## Connexion
Pour toutes les url qui on comment préfix `/api` il vous faut ajouter un champ `token` avec la bonne valeur, vous pouvez récupérer ce token à l'url `/api/login` 


## GET /api/getLocation

Pour récupérer un Event il vous faut interroger l'API dans les **5 minutes** avant ou après la date contenu dans le champ `StartAt`

## POST /api/checkIn

Pour valider un Event il vous faut interroger l'API dans les **5 minutes** avant ou après la date contenu dans le champ `StartAt` ainsi que la bonne valeur pour la data du QRCode et la bonne valeur de `Beacon`
