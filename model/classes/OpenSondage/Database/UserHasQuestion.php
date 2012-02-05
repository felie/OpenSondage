<?php

namespace OpenSondage\Database;

use OpenSondage\Database\om\BaseUserHasQuestion;


/**
 * Skeleton subclass for representing a row from the 'user_has_question' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.OpenSondage.Database
 */
class UserHasQuestion extends BaseUserHasQuestion
{
  public function save(\PropelPDO $con = null)
  {
    $affectedRows = parent::save($con);
    
    // recalculate the result of the question
    $value_of_maybe = $this->getQuestion()->getPoll()->getNbPointMaybe();
    
    $value_sum = 'SUM( IF( '.UserHasQuestionPeer::YES.'=1, 1, IF('.UserHasQuestionPeer::MAYBE.'=1, '.str_replace(',', '.', $value_of_maybe).', 0)))';
    
    $stmt = UserHasQuestionQuery::create()
            ->withColumn($value_sum, 'value_sum')
            ->where(UserHasQuestionPeer::QUESTION_ID, $this->getQuestionId())
            ->addGroupByColumn(UserHasQuestionPeer::QUESTION_ID)
            ->select('value_sum')
            ->findOne();
    
    if ($stmt !== null) {
      $this->getQuestion()->setResult($stmt);
      $this->getQuestion()->save($con);
    }
    
    return $affectedRows;
  }
} // UserHasQuestion
