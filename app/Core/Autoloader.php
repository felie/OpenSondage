<?php
namespace OpenSondage\Core;


/**
 * Autoloader class
 *
 * @package     OpenSondage
 * @subpackage  OpenSondage\Core
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Autoloader
{
  /**
   * Registers OpenSondage\Core\Autoloader as an SPL autoloader.
   */
  public static function register()
  {
    spl_autoload_register(__NAMESPACE__.'\Autoloader::autoload', true);
  }
  
  
  public static function autoload($class)
  {
    // if second level of the namespace is OpenSondage\Database
    // use self::autoloadModel()
    if (strpos($class, 'Symfony') === 0) {
      self::autoloadSymfony($class);
      return;
    }
    
    // if first level of the namespace isn't OpenSondage
    // then this isn't an OpenSondage class
    if (strpos($class, 'OpenSondage') !== 0) {
      return;
    }
    
    // if second level of the namespace is OpenSondage\Database
    // use self::autoloadModel()
    if (strpos($class, 'OpenSondage\\Database') === 0) {
      self::autoloadModel($class);
      return;
    }
    
    $parts = explode('\\', $class);
    if (count($parts) < 2) {
      return;
    }
    
    unset($parts[0]);
    
    $directory  = Config::get('app_dir');
    
    self::includeFile($directory, $parts);
  }
  
  private static function autoloadModel($class)
  {
    $directory  = Config::get('model_dir');
    $parts      = explode('\\', $class);
    
    self::includeFile($directory, $parts);
  }
  
  private static function autoloadSymfony($class)
  {
    $directory  = Config::get('vendor_dir');
    $parts      = explode('\\', $class);
    
    self::includeFile($directory, $parts);
  }
  
  private static function includeFile($directory, $parts)
  {
    $filename = $directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $parts).'.php';
    
    if (is_file($filename) === true) {
      require_once $filename;
    }
  }
}