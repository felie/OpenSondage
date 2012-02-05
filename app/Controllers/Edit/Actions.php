<?php
namespace OpenSondage\Controllers\Edit;

use OpenSondage\Core\Controllers;
use OpenSondage\Core\Exception;
use OpenSondage\Core\Http\Request;
use OpenSondage\Database;

/**
 * Actions class
 *
 * @package     OpenSondage\Controllers
 * @subpackage  OpenSondage\Controllers\Edit
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
   * Redirect in the good page according to type of poll
   *
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeDefault(Request $request)
  {
    $type = (string)$request->get('type');
    
    if ($type === 'poll') {
      $this->getResponse()->redirect('Edit/CreatePoll');
    } elseif ($type === 'meeting') {
      $this->getResponse()->redirect('Edit/CreateMeeting');
    } else {
      throw new Exception\Exception404('Impossible to create an other type of poll');
    }
  }
  
  
  /**
   * Show page to create a poll
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeCreatePoll(Request $request)
  {
    $errors = array();
    $list_questions = array();
    
    $this->poll = new Database\Poll();
    
    if ($request->getMethod() === 'POST') {
      $this->poll->setName($request->get('name'));
      $this->poll->setDescription($request->get('description'));
      $this->poll->setUsername($request->get('username'));
      $this->poll->setMail($request->get('mail'));
      $this->poll->setMaybeAuthorized($request->get('maybe_authorized', 0));
      $this->poll->setNbPointMaybe($request->get('nb_point_maybe'));
      $this->poll->setAllowModified($request->get('allow_modified', 0));
      $this->poll->setMailmodified($request->get('mail_modified', 0));
      
      // Set the default value
      $this->poll->initUid();
      $this->poll->setType(Database\PollPeer::TYPE_POLL);
      
      // check question
      $questions = $request->get('question');
      if (is_array($questions) === false) {
        $errors[] = 'You must enter at least one question';
      }
      foreach ($questions as $question) {
        $question = trim($question);
        if (empty($question) === false) {
          $list_questions[] = $question;
        }
      }
      
      if (count($list_questions) === 0) {
        $errors[] = 'You must enter at least one question';
      }
      
      if (count($errors) === 0 && $this->poll->validate() === true) {
        // Add questions
        $rank = 0;
        foreach ($list_questions as $item) {
          $question = new Database\Question();
          $question->setName($item);
          $question->setResult(0);
          $question->setSortableRank(++$rank);
          $this->poll->addQuestion($question);
        }
        
        $this->poll->save();
        
        $this->getResponse()->redirect('Poll/Default?public_uid='.$this->poll->getPublicUid());
      } else {
        
        foreach ($this->poll->getValidationFailures() as $error) {
          $errors[] = $error;
        }
        
      }
    }
    
    if (count($list_questions) < \OpenSondage\Core\Config::get('default_nb_question')) {
      $this->list_questions = array_merge($list_questions, array_fill(0, \OpenSondage\Core\Config::get('default_nb_question') - count($list_questions), ''));
    } else {
      $this->list_questions = $list_questions;
    }
    $this->errors = $errors;
  }
  
  
  /**
   * Show page to create a meeting's poll
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeCreateMeeting(Request $request)
  {
    
  }
}