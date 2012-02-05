<?php
namespace OpenSondage\Core;


/**
 * Controllers class
 *
 * @package     OpenSondage
 * @subpackage  OpenSondage\Core
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 * @abstract
 */
abstract class Controllers
{
  private $template = null;
  private $vars     = array();
  
  private $session  = null;
  private $request  = null;
  private $response = null;
  
  public function __construct(Http\Request $request, Http\Response $response, Http\Session $session, $action)
  {
    $this->request  = $request;
    $this->response = $response;
    $this->session  = $session;
    
    $this->template = $this->buildTemplateName($action);
    
    // Add somes default vars
    $this->vars['request'] = $this->request;
    $this->vars['base_url'] = $this->request->getBasePath();
    $this->vars['css_url'] = $this->request->getBasePath().'/css';
    $this->vars['js_url'] = $this->request->getBasePath().'/js';
    $this->vars['images_url'] = $this->request->getBasePath().'/images';
    $this->vars['website_name'] = Config::get('website_name');
    $this->vars['website_logo'] = Config::get('website_logo');
    $this->vars['output_compress'] = Config::get('compress');
  }
  
  public function preExecute(){}
  public function postExecute(){}
  
  public function setTemplate($template)
  {
    if (strpos($template, '.') === false) {
      $template = $this->buildTemplateName($template);
    }
    
    $this->template = $template;
  }
  
  
  public function getTemplate()
  {
    return $this->template;
  }
  
  public function getRequest()
  {
    return $this->request;
  }
  
  public function getResponse()
  {
    return $this->response;
  }
  
  public function getSession()
  {
    return $this->session;
  }
  
  private function buildTemplateName($action)
  {
    if (strpos($action, 'execute') === 0) {
      $action = substr($action, strlen('execute'));
    }
    
    $template = str_replace(
      array('OpenSondage\\Controllers', '\\Actions', '\\'),
      array('', '', '.'),
      get_class($this));
    $template .= '.'.$action;
    
    return $template;
  }
  
  
  public function getTemplatePath()
  {
    return str_replace('.', DIRECTORY_SEPARATOR, $this->template).'.html';
  }
  
  public function getVars()
  {
    return $this->vars;
  }
  
  
  public function __set($name, $value)
  {
    $this->vars[$name] = $value;
  }
  
  public function __get($name)
  {
    if (array_key_exists($name, $this->vars) === false) {
      throw new Exception('Undefined property : '.$name);
    }
    
    return $this->vars[$name];
  }
}