<?php
namespace OpenSondage\Core\Http;

use OpenSondage\Core\Exception;


/**
 * Routing class
 *
 * @package     OpenSondage\Core
 * @subpackage  OpenSondage\Core\Http
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Routing
{
  private static $routes = array();
  private static $reverse_routes = array();
  
  private static $controller  = 'Index';
  private static $action      = 'Default';
  private static $arguments   = array();
  
  public static function add(array $routes)
  {
    foreach ($routes as $route => $datas) {
      $parts = explode('/', $route);
      $arguments = array();
      
      if (isset($datas['controller']) === false) {
        throw new Exception('controller isn\'t defined in the route');
      }
      
      for ($count = count($parts), $i = 0; $i < $count; $i++) {
        if (strpos($parts[$i], ':') === 0) {
          $arguments[] = substr($parts[$i], 1);
          $parts[$i] = '([^/]+)';
        }
      }
      
      $regexp_route = implode('/', $parts);
      
      self::$routes[$regexp_route] = array(
        'arguments'   => $arguments,
        'controller'  => isset($datas['controller']) ? $datas['controller'] : self::$controller,
        'action'      => isset($datas['action']) ? $datas['action'] : self::$action,
      );
      
      $key = sha1(self::$routes[$regexp_route]['controller'].'/'.self::$routes[$regexp_route]['action'].'/'.implode('/', $arguments));
      self::$reverse_routes[$key] = array(
        'route'     => $route,
        'arguments' => $arguments,
      );
    }
  }
  
  public static function getReverseRouting()
  {
    return self::$reverse_routes;
  }
  
  public static function getController()
  {
    return self::$controller;
  }
  
  public static function getAction()
  {
    return self::$action;
  }
  
  public static function getArguments()
  {
    return self::$arguments;
  }
  
  public static function current(Request $request)
  {
    $uri = $request->getPathInfo();
    foreach (self::$routes as $route => $datas) {
      if (preg_match('|^'.$route.'$|', $uri, $matches) > 0) {
        if (isset($datas['action']) === true) {
          self::$action = $datas['action'];
        }
        
        if (isset($datas['action']) === true) {
          self::$controller = $datas['controller'];
        }
        
        if (isset($datas['arguments']) === true && count($datas['arguments']) > 0) {
          $arguments = $datas['arguments'];
          $nb_arguments = count($arguments);
          for ($i = 0; $i < $nb_arguments; $i++) {
            self::$arguments[$arguments[$i]] = $matches[$i + 1];
          }
        }
        
        return;
      }
    }
    
    throw new Exception('No route found');
  }
  
  public static function extractArguments($arguments)
  {
    $names = array();
    $arguments = explode('&', $arguments);
    
    foreach ($arguments as $argument) {
      $datas = explode('=', $argument);
      if (is_array($datas) === true && count($datas) === 2) {
        $names[] = $datas[0];
      }
    }
    
    return $names;
  }
}