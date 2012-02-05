<?php
namespace OpenSondage\Controllers\Poll;

use OpenSondage\Core\Controllers;
use OpenSondage\Core\Exception;
use OpenSondage\Core\Http\Request;
use OpenSondage\Database;

/**
 * Actions class
 *
 * @package     OpenSondage\Controllers
 * @subpackage  OpenSondage\Controllers\Poll
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Actions extends Controllers
{
  /**
   * Execute code before all execute method
   *
   * @access  public
   */
  public function preExecute()
  {
    setlocale(LC_ALL, \OpenSondage\Core\Config::get('locale').'.utf8');
  }
  
  
  /**
   * Show a public poll
   *
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeDefault(Request $request)
  {
    $public_uid = (string)$request->get('public_uid');
    $this->user_id = (int)$request->get('user_id');
    
    $this->poll = Database\PollQuery::create()->findOneByPublicUid($public_uid);
    
    if ($this->poll === null) {
      throw new Exception\Exception404('No poll found');
    }
    
    if ($this->poll->getType() === Database\PollPeer::TYPE_POLL) {
      $this->setTemplate('ViewPoll');
    } elseif ($this->poll->getType() === Database\PollPeer::TYPE_MEETING) {
      $this->setTemplate('ViewMeeting');
    }
    
    $this->can_edit = $this->poll->getAllowModified();
    
    // Questions
    $criteria = Database\QuestionQuery::create()->orderByRank('asc');
    $this->questions = $this->poll->getQuestions($criteria);
    
    // Users
    $criteria = Database\UserQuery::create()->orderByRank('asc');
    $this->users = $this->poll->getUsers($criteria);
    
    // Comments
    $criteria = Database\CommentQuery::create()->lastCreatedFirst();
    $this->comments = $this->poll->getComments($criteria);
    
    // Possible responses
    if ($this->poll->getMaybeAuthorized() === true) {
      $this->responses = array(
        Database\UserHasQuestionPeer::VALUE_NO => 'No',
        Database\UserHasQuestionPeer::VALUE_MAYBE => 'Maybe',
        Database\UserHasQuestionPeer::VALUE_YES => 'Yes',
      );
    } else {
      $this->responses = array(
        Database\UserHasQuestionPeer::VALUE_NO => 'No',
        Database\UserHasQuestionPeer::VALUE_YES => 'Yes',
      );
    }
  }
  
  
  /**
   * Save a new responses
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeResponse(Request $request)
  {
    $public_uid = (string)$request->get('public_uid');
    $names = $request->get('name');
    $responses = $request->get('response');
    $save = $request->getRegexp('/save_[0-9]+/', $key);
    
    $this->poll = Database\PollQuery::create()->findOneByPublicUid($public_uid);
    
    // Check if the poll exists
    if ($this->poll === null) {
      throw new Exception\Exception404('No poll found');
    }
    
    // Check if the responses are allowed
    if (is_array($responses) === false) {
      throw new Exception\ExceptionSecurity('arguments are not allowed');
    }
    
    $keys = array_keys($responses);
    
    // Check if the old responses can be modified
    if ($this->poll->getAllowModified() === false && max($keys) !== 0) {
      throw new Exception\ExceptionSecurity('you are not allowed to modified this response');
    }
    
    // Save response
    $key = (int)substr($key, 5);
    if (isset($responses[$key]) === false || isset($names[$key]) === false) {
      throw new Exception\ExceptionSecurity('your response or name doesn\'t exist');
    }
    
    $response = $responses[$key];
    $name = $names[$key];
    
    $criteria = Database\QuestionQuery::create()->orderByRank('asc');
    $questions = $this->poll->getQuestions($criteria);
    
    if ($key == 0) {
      // new response
      $user = new Database\User();
      $user->setName($name);
      $user->setPoll($this->poll);
      $user->save();
      
      foreach ($response as $question_id => $value) {
        $question = Database\QuestionQuery::create()->findPk($question_id);
        if ($question === null || $question->getPollId() !== $this->poll->getId()) {
          throw new Exception\ExceptionSecurity('this question '.$question_id.' isn\'t linked with the poll '.$this->poll->getId());
        }
        
        $user_has_question = new Database\UserHasQuestion();
        $user_has_question->setUser($user);
        $user_has_question->setQuestion($question);
        
        if ($value === Database\UserHasQuestionPeer::VALUE_YES) {
          $user_has_question->setYes(true);
        } elseif ($value === Database\UserHasQuestionPeer::VALUE_NO) {
          $user_has_question->setNo(true);
        } elseif ($this->poll->getMaybeAuthorized() === true && $value === Database\UserHasQuestionPeer::VALUE_MAYBE) {
          $user_has_question->setMaybe(true);
        } else {
          throw new Exception\ExceptionSecurity('this response isn\'t authorized');
        }
        
        $user_has_question->save();
      }
    } else {
      // edit response
      $user = Database\UserQuery::create()->findPk($key);
      if ($user === null) {
        throw new Exception\ExceptionSecurity('the user is unknow');
      }
      
      foreach ($questions as $question) {
        $user_has_question = $user->getUserHasQuestion($question);
        if ($user_has_question === null) {
          
        }
      }
    }
    
    $this->getResponse()->redirect('Poll/Default?public_uid='.$public_uid);
  }
}