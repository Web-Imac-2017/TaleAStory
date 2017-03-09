<?php
namespace Model;

class SpecificChoice extends Choice {

  public function __construct($answer,$idStep,$transitionText,$idNextStep){
    parent::__construct($answer,$idStep,$transitionText,$idNextStep);
  }

  /*
  @function checkAnswer
  @param  $answer
  @return $message d'erreur pour le joueur
  check si l'answer donnée par le joueur correspond bien à une answer du choix (dans la bd)
  */
  public function checkAnswer($answer){
    $res = parent::checkAnswer($answer);
    if($res){
      return Response::jsonResponse(array(
        'status' => "ok",
        'message' => "Tu as bon !"
      ));
    }
    else {
      return Response::jsonResponse(array(
        'status' => "error",
        'message' => "Tout faux !"
      ));
    }
  }
}
?>
