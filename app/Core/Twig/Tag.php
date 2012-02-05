<?php

namespace OpenSondage\Core\Twig;


/**
 * Tag class
 *
 * @package     OpenSondage\Core
 * @subpackage  OpenSondage\Core\Twig
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Tag extends \Twig_Extension
{
  private static $path_class = null;
  
  public function getName()
  {
    return 'tag';
  }
  
  public function getFunctions()
  {
    return array(
      'image_tag'  => new \Twig_Function_Method($this, 'image', array('is_safe' => array('html'))),
      'css_tag'    => new \Twig_Function_Method($this, 'stylesheet', array('is_safe' => array('html'))),
      'js_tag'     => new \Twig_Function_Method($this, 'javascript', array('is_safe' => array('html'))),
    );
  }
  
  
  public function image($image, $options = array())
  {
    $image_path = self::getPathClass()->image($image, self::isAbsolute($options));
    
    if (isset($options['alt']) === false) {
      $options['alt'] = pathinfo($image, PATHINFO_BASENAME);
    }
    
    return '<img src="'.$image_path.'"'.self::getAttr($options).' />';
  }
  
  public function stylesheet($stylesheet, $options = array())
  {
    $stylesheet_path = self::getPathClass()->stylesheet($stylesheet, self::isAbsolute($options));
    
    if (isset($options['media']) === false) {
      $options['media'] = 'screen';
    }
    
    return '<link rel="stylesheet" type="text/css" href="'.$stylesheet_path.'"'.self::getAttr($options).' />';
  }
  
  public function javascript($javascript, $options = array())
  {
    $javascript_path = self::getPathClass()->javascript($javascript, self::isAbsolute($options));
    
    if (isset($options['type']) === false) {
      $options['type'] = 'text/javascript';
    }
    
    return '<script src="'.$javascript_path.'"'.self::getAttr($options).'></script>';
  }
  
  private static function isAbsolute($options)
  {
    $absolute = false;
    if (isset($options['absolute']) === true) {
      if ($options['absolute'] === true) {
        $absolute = true;
      }
      unset($options['absolute']);
    }
    
    return $absolute;
  }
  
  
  private static function getAttr($options)
  {
    $attributes = '';
    
    foreach ($options as $attr => $value) {
      $attributes .= ' '.$attr.'="'.str_replace('"', '\\"', $value).'"';
    }
    
    return $attributes;
  }
  
  
  private static function getPathClass()
  {
    if (self::$path_class === null) {
      self::$path_class = new Path();
    }
    
    return self::$path_class;
  }
}