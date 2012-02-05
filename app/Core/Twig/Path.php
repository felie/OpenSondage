<?php

namespace OpenSondage\Core\Twig;


/**
 * Path class
 *
 * @package     OpenSondage\Core
 * @subpackage  OpenSondage\Core\Twig
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Path extends \Twig_Extension
{
  private static $base_path = null;
  private static $absolute_base_path = null;
  
  public function getName()
  {
    return 'path';
  }
  
  public function getFunctions()
  {
    return array(
      'image_path'  => new \Twig_Function_Method($this, 'image'),
      'css_path'    => new \Twig_Function_Method($this, 'stylesheet'),
      'js_path'     => new \Twig_Function_Method($this, 'javascript'),
      'base_path'   => new \Twig_Function_Method($this, 'base'),
    );
  }
  
  
  public function image($image, $absolute = false)
  {
    return self::getItemPath($image, '/images/'.\OpenSondage\Core\Config::get('template').'/', $absolute);
  }
  
  public function stylesheet($stylesheet, $absolute = false)
  {
    return self::getItemPath($stylesheet, '/css/'.\OpenSondage\Core\Config::get('template').'/', $absolute);
  }
  
  public function javascript($javascript, $absolute = false)
  {
    return self::getItemPath($javascript, '/js/', $absolute);
  }
  
  public function base($url, $absolute = false)
  {
    return self::getItemPath($url, '/', $absolute);
  }
  
  
  private static function getItemPath($item, $folder, $absolute = false)
  {
    if (substr($item, 0, 4) === 'http') {
      return $item;
    }
    
    if (substr($item, 0, 1) === '/') {
      $item = $item;
    } else {
      $item = $folder.$item;
    }
    
    if ($absolute === true){
      $item = self::getAbsoluteBasePath().$item;
    } else {
      $item = self::getBasePath().$item;
    }
    
    return $item;
  }
  
  private static function getBasePath()
  {
    if (self::$base_path === null) {
      $request = \OpenSondage\Core\Http\Request::createFromGlobals();
      self::$base_path = $request->getBasePath();
    }
    
    return self::$base_path;
  }
  
  
  private static function getAbsoluteBasePath()
  {
    if (self::$absolute_base_path === null) {
      $request = \OpenSondage\Core\Http\Request::createFromGlobals();
      self::$absolute_base_path = $request->getUriForPath('');
    }
    
    return self::$absolute_base_path;
  }
}