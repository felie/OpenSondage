<?php
namespace OpenSondage\Core\Http;

use OpenSondage\Core\Exception;


/**
 * Request class
 *
 * @package     OpenSondage\Core
 * @subpackage  OpenSondage\Core\Http
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Request extends \Symfony\Component\HttpFoundation\Request
{
  public function getUrlFor($route)
  {
    $position = strpos($route, '?');
    
    if ($position > 0) {
      $controller_action = substr($route, 0, $position);
      $args = substr($route, $position + 1);
      $key = sha1($controller_action.'/'.implode('/', Routing::extractArguments($args)));
    } else {
      $controller_action = $route;
      $args = '';
      $key = sha1($controller_action.'/');
    }
    
    $reverse_routing = Routing::getReverseRouting();
    if (isset($reverse_routing[$key]) === false) {
      throw new Exception\ExceptionRouting('no route found for : '.$route);
    }
    
    $url = $reverse_routing[$key]['route'];
    $arguments = $reverse_routing[$key]['arguments'];
    
    $args = explode('&', $args);
    
    foreach ($args as $arg) {
      $explode_args = explode('=', $arg);
      
      if (count($explode_args) === 2) {
        $url = str_replace(':'.$explode_args[0], $explode_args[1], $url);
      }
    }
    
    return $url;
  }
  
  public function getRegexp($regexp, &$key_name, $default = null, $deep = false)
  {
    $datas = array();
    $datas = array_merge($datas, $this->query->all());
    $datas = array_merge($datas, $this->request->all());
    $datas = array_merge($datas, $this->server->all());
    $datas = array_merge($datas, $this->cookies->all());
    
    $keys = array_keys($datas);
    
    foreach ($keys as $key) {
      if (preg_match($regexp, $key) === 1) {
        $key_name = $key;
        return $this->get($key, $default, $deep);
      }
    }
    
    return $default;
  }
}