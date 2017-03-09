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

<?php

class stepController {

  public static function stepCount() {
    return step::countSteps();
  }

  public static function stepSave() { //Fait par Anne & Olivier
  }

  public static function getStepsList($start, $count) {
    $stepArray = [];
    $case=0;

    for ($i=$start; $i<$start + $count; $i++, $case++) {
      $stepParam = Database::instance()->query("Type", Array("IDStep"=> $i, "ImgPath"=>"","Body"=>"", "Question"=>"", "IDType"=>""));
      $stepArray[$case] = New Step($stepParam["ImgPath"],$stepParam["Body"],$stepParam["Question"],$stepParam["IDType"]);
    }
    return $stepArray;

  }

  public static function getTenStepsList($start) {
    $stepArray = [];
    $case=0;
    for ($i=$start; $i<$start + 10; $i++, $case++) {
      $stepParam = Database::instance()->query("Type", Array("IDStep"=> $i, "ImgPath"=>"","Body"=>"", "Question"=>"", "IDType"=>""));
      $stepArray[$case] = New Step($stepParam["ImgPath"],$stepParam["Body"],$stepParam["Question"],$stepParam["IDType"]);
    }
    return $stepArray;
  }

  public static function getStep() { //récuperer les options grâce au module global : Form.php ->
    Form::getFormPost( );

  }

  public static function stepResponse(){
    $answer = Form::getField( );
    $id = Session::getCurrentUser;
    $player = Player::getPlayer($id);
    $CurrentStep_TMP = $player->currentStep();
    $Step_TMP = New Step;


    if ($player == NULL) {
      echo "Joueur introuvable, IDPlayer incorrect."
    }
    else {
      $array = $Step_TMP->processAnswer($player,$answer);
      return Response::jsonResponse($array);
    }
  }
}
 ?>
