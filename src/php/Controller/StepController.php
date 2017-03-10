<?php
namespace Controller;
use \Server\Database;
use \Server\Form;
use \Model\Step;
use \Server\Response;
use \View\Success;
use \View\Error;

class StepController {

  public static function stepCount() {
    return Step::countSteps();
  }

  public static function getStepsList($start, $count) {
      $stepParam = Database::instance()->query("Step", Array("IDStep"=> "",
                                                            "ImgPath"=>"",
                                                            "Body"=>"",
                                                            "Question"=>"",
                                                            "IDType"=>""),
                                                          "LIMIT ".$count." OFFSET ".$start);

    response::jsonResponse($stepParam);
  }

  public static function getTenStepsList($start) {
    $stepParam = Database::instance()->query("Step", Array("IDStep"=> "",
                                                          "ImgPath"=>"",
                                                          "Body"=>"",
                                                          "Question"=>"",
                                                          "IDType"=>""),
                                                        "LIMIT 10 OFFSET ".$start);

    response::jsonResponse($stepParam);
  }

  public static function getStep() { //récuperer les options grâce au module global : Form.php ->
    Form::getFormPost( );

  }

  public static function stepResponse(){
    $answer = \Server\Form::getField("answer");
    echo $answer;
    $id = 2; //\Server\Session::getCurrentUser();
    $player = \Model\Player::getPlayer($id);
    var_dump($player);


    if ($player == NULL) {
      echo "Joueur introuvable, IDPlayer incorrect.";
    }
    else {
        $CurrentStep = $player->currentStep();
        $array = $CurrentStep->processAnswer($player,$answer);
        var_dump($array);return Response::jsonResponse($array);
    }
  }


  public static function addStep() {
    //$imgpath = Form::uploadFile("stepImg");
    $imgpath = "bidon";
    $body = Form::getField("Body");
    $question = Form::getField("Question");
    $accepted = 1;
    $idType = Form::getField("IDType");
    if ($imgpath == NULL || $body == NULL || $question == NULL || $accepted == NULL || $idType == NULL){
      $e = new Error("Tu ne peux pas ajouter cette péripéthie !");
      return Response::jsonResponse($e);
    }

    $step = new Step($imgpath, $body, $question, $accepted, $idType);
    $step = $step->save();
    if($step)
      $e = new Success("La péripéthie a été ajoutée.");
    else
      $e = new Error("Tu ne peux pas ajouter cette péripéthie !");
    return Response::jsonResponse($e);
  }

  public static function updateStep() {
    //$tmp = $imgpath;
    //$imgpath = Form::uploadFile("stepImg");
    //unlink ($tem);

    $data = Form::getFullForm(); //si l'id n'est pas présent, on retourne null
    //var_dump($data);
    if(!isset($data["IDStep"]) || $data["IDStep"]== null ){
      $e = new Error("Péripéthie invalide");
      return Response::jsonResponse($e);
    }

    $entries = array();
    $fields = array("IDStep","ImgPath","Body","Question","IDType");
    foreach ($fields as $field) {
      if(!isset($data[$field]) || $data[$field]=="") {
        if(substr($field,0,2) != "ID") //les champs ID inchangés (donc initialisés à null dans data) ne doivent pas être précisés dans entries
          $entries[$field]="";
      }
      else {
        $entries[$field]=$data[$field];
      }
    }
    $step = new Step("bidon", $entries["Body"], $entries["Question"],1, 0);
    $step->id = $entries["IDStep"];
    var_dump($entries);
    $step->update($entries);
    $e = new Success("Péripéthie modifiée !");
    return Response::jsonResponse($e);
  }

  public static function deleteStep() {
    $id = Form::getField("IDStep");
    if(!$id){
      $e = new Error("Impossible de supprimer la péripéthie !");
      return Response::jsonResponse($e);
    }
    else{
      $step = new Step("", "", "", 0,0);
      $step->id = $id;
      $step = $step->delete();
      if($step)
        $e = new Success("Péripéthie supprimée !");
      else
        $e = new Error("Impossible de supprimer la péripéthie !");
      return Response::jsonResponse($e);
    }
  }
}
?>
