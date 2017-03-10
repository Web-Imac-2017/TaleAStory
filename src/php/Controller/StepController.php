<?php
namespace Controller;
use \Server\Database;
use \Server\Form;
use \Model\Step;
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
        var_dump($array);return Response:jsonResponse($array);
    }
  }


  public static function addStep() {
    $imgpath = Form::uploadFile("stepImg");
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    if ($imgpath == NULL || $body == NULL || $question == NULL || $accepted == NULL || $idType == NULL)
    return Response::jsonResponse(array(
      'status' => "error",
      'message' => "ta race tu ne peux pas ajouter ce step !"
    ));
    $step = new Step($imgpath, $body, $question, $accepted, $idType);
    $step->save();
    Response::jsonResponse($step);
  }
  public static function updateStep() {
    $tmp = $imgpath;
    $imgpath = Form::uploadFile("stepImg");
    // récupérer 1 obj avec soit statut "error" ou "ok", selon le statut : message d'erreur ou nom de l'image (faire des if)
    $body = Form::getField("body");
    $question = Form::getField("question");
    $accepted = Form::getField("accepted");
    $idType = Form::getField("idType");
    $item = new Step($imgpath, $body, $question, $accepted, $idType);
    $item->id = $id;
    $entries = array();
    if ($imgpath != NULL)
      array_push($entries, $name);
    if ($body != NULL)
      array_push($entries, $name);
    if ($question != NULL)
      array_push($entries, $name);
    if ($accepted != NULL)
      array_push($entries, $name);
    if ($idType != NULL)
      array_push($entries, $name);
    $item->update($entries);
  }
  public static function deleteStep() {
    $id = Form::getField("id");
    $step = new Step("", "", "", "","");
    $step->id = $id;
    $step->delete();
  }
}
?>
