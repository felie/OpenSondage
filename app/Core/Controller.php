<?php
namespace OpenSondage\Core;

use Symfony\Component\HttpFoundation\SessionStorage\NativeSessionStorage;


/**
 * Controller class
 *
 * @package     OpenSondage
 * @subpackage  OpenSondage\Core
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Controller
{
  private $session  = null;
  private $request  = null;
  private $response = null;
  
  public function __construct(Http\Request $request)
  {
    $this->request  = $request;
    $this->session  = new Http\Session(new NativeSessionStorage());
    $this->response = new Http\Response();
  }
  
  public function execute($controller, $action)
  {
    $controller_name  = 'OpenSondage\\Controllers\\'.$controller.'\\Actions';
    $methode_name     = 'execute'.$action;
    
    if ($this->exists($controller_name, $methode_name) === false) {
      throw new Exception\Exception404($controller.'/'.$action.' doesn\'t exist');
    }
    
    $o_controller = new $controller_name($this->request, $this->response, $this->session, $action);
    
    $o_controller->preExecute();
    $o_controller->$methode_name($o_controller->getRequest());
    $o_controller->postExecute();
    
    return $o_controller;
  }
  
  private function exists($controller_name, $methode_name)
  {
    return is_callable(array($controller_name, $methode_name));
  }
}