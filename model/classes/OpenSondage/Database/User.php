<?php

namespace OpenSondage\Database;

use OpenSondage\Database\om\BaseUser;


/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.OpenSondage.Database
 */
class User extends BaseUser
{
  private $response = array();
  
  public function getResponse(Question $question)
  {
    $user_has_question = $this->getUserHasQuestion($question);
    
    if ($user_has_question !== null) {
      if ($user_has_question->getYes() === true) {
        return UserHasQuestionPeer::VALUE_YES;
      } elseif ($user_has_question->getNo() === true) {
        return UserHasQuestionPeer::VALUE_NO;
      } elseif ($user_has_question->getMaybe() === true) {
        return UserHasQuestionPeer::VALUE_MAYBE;
      }
    }
    
    return null;
  }
  
  
  public function getUserHasQuestion(Question $question)
  {
    if (key_exists($question->getId(), $this->response) === false) {
      $user_has_question = UserHasQuestionQuery::create()
                            ->setQueryKey('search response by user')
                            ->filterByUser($this)
                            ->filterByQuestion($question)
                            ->findOne();
      $this->response[$question->getId()] = $user_has_question;
    } else {
      $user_has_question = $this->response[$question->getId()];
    }
    
    return $user_has_question;
  }
} // User
