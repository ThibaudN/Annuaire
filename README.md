# Annuaire

Configurer paypal
-----------------
1. Se connecter à paypal avec son compte business
2. Aller sur cette page pour créer un bouton https://www.paypal.com/buttons/select/saved
3. Options paypal : 
- Bouton acheter
- Choisir un "Nom de l'objet"
- Choisir un Prix (A reporter dans la configuration du backoffice)
- Utiliser mon identifiant de compte marchand sécurisé (sélectionner cette option)
- Etape 2 : Enregistrer le bouton auprès de PayPal (et ne pas suivre la gestion des stocks)
- Etape 3 : Souhaitez-vous permettre à votre client de modifier les quantités commandées ? : Non
- Etape 3 : Votre client peut-il ajouter des instructions particulières dans un message à votre intention ? : Non
- Etape 3 : Avez-vous besoin de l'adresse de livraison de votre client ? : Non
- Etape 3 : Diriger vos clients vers cette URL s’ils annulent leur paiement : URLDUDOMAINE/proposerunsite_paiement.php?erreur=1
- Etape 3 : Diriger vos clients vers cette URL lorsqu’ils terminent leur paiement : URLDUDOMAINE/proposerunsite_paiement.php?done=1
- Etape 3 : variables avancées : notify_url=URLDUDOMAINE/a_paypal.php
4. Reporter l'ID du bouton enregistré chez paypal dans le backoffice
