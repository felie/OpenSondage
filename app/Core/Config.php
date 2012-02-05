<?php
namespace OpenSondage\Core;


/**
 * Config class
 *
 * @package     OpenSondage
 * @subpackage  OpenSondage\Core
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Config
{
  protected static $values = array();
  
  public static function set($name, $value)
  {
    self::$values[$name] = $value;
  }
  
  
  public static function get($name)
  {
    if (isset(self::$values[$name]) === false) {
      return null;
    }
    
    return self::$values[$name];
  }
  
  
  public static function add($config)
  {
    if (is_array($config) === false) {
      throw new Exception('config must be an array');
    }
    
    foreach ($config as $key => $value) {
      self::set($key, $value);
    }
  }
}