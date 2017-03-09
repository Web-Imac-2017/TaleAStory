/*
step/count/
    -> renvoie un entier correspondant au nombre de step créé
    -> il faut attendre les fonctions de Lou qui comptent dans la base de donnée.

step/list/:start/:count
    -> renvoie une liste de step, la liste commence à partir de :start step et en contient :count

step/list/:start/
    -> renvoie une liste de step, la liste commence à partir de :start step et en contient 10

step/list (POST : 'application/json')
    -> renvoie une liste de step selon les options envoyées

step/save (POST : 'application/json')
    -> crée un Step dans la base de donnée, en faisant le lien entre le front et la base de donnée
    (récupération des données du front genre formulaires etc... utiliser la fonction save() de step)

currentstep/response (POST : 'application/json')
    -> traite la réponse reçus et renvoie un status selon la validité de celle-ci

*/
