<?php
namespace OpenSondage\Controllers\Index;

use OpenSondage\Core\Controllers;
use OpenSondage\Core\Http\Request;
use OpenSondage\Database;

/**
 * Actions class
 *
 * @package     OpenSondage\Controllers
 * @subpackage  OpenSondage\Controllers\Index
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Actions extends Controllers
{
  /**
   * Show the homepage
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeDefault(Request $request)
  {
  }
  
  
  /**
   * Show the about page
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeAbout(Request $request)
  {
  }
  
  
  /**
   * Show the contact page
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeContact(Request $request)
  {
    $errors = array();
    $this->name = $this->email = $this->message = '';
    
    if ($request->getMethod() === 'POST') {
      $this->name     = (string)$request->get('name');
      $this->email    = (string)$request->get('email');
      $this->message  = (string)$request->get('message');
      
      $this->name     = trim($this->name);
      $this->email    = trim($this->email);
      $this->message  = trim($this->message);
      
      if ($this->name === '') {
      var_dump($this->name, empty($this->name));die();
        $errors[] = 'Your name is required';
      }
      if ($this->email === '') {
        $errors[] = 'Your email is required';
      } elseif (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Your email isn\'t valid';
      }
      if ($this->message === '') {
        $errors[] = 'Your message is required';
      }
      
      if (count($errors) === 0) {
        /**
         * @todo : Send mail
         */
        
        $this->getResponse()->redirect('Index/Sended');
      }
    }
    
    $this->errors = $errors;
  }
  
  
  /**
   * Show the contact page when email is sended
   * 
   * @param   OpenSondage\Core\Http\Request   $request    The Request object
   * @access  public
   */
  public function executeSended(Request $request)
  {
  }
}