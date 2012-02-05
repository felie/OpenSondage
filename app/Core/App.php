<?php
namespace OpenSondage\Core;

require_once __DIR__.DIRECTORY_SEPARATOR.'Autoloader.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'Config.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'Exception.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Routing.php';


/**
 * App class
 *
 * @package     OpenSondage
 * @subpackage  OpenSondage\Core
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class App
{
  private static $base_dir            = null;
  private static $app_dir             = null;
  private static $config_dir          = null;
  private static $core_dir            = null;
  private static $controller_dir      = null;
  private static $templates_base_dir  = null;
  private static $vendor_dir          = null;
  
  private static $controller          = null;
  private static $request             = null;
  private static $routing             = null;
  
  /**
   * Dispatch the request
   *
   * @access  public
   * @static
   */
  public static function run()
  {
    // initialize
    self::init();
    
    // setup configuration
    self::setup();
    
    // Launch autoload
    self::autoload();
    
    // get routing
    self::getRouting();
    
    // launch controler
    self::getController();
    
    // send view
    self::getView();
  }
  
  
  /**
   * Initialize all base path
   * 
   * @access  private
   * @static
   */
  private static function init()
  {
    self::$base_dir           = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..');
    self::$app_dir            = self::$base_dir.DIRECTORY_SEPARATOR.'app';
    self::$config_dir         = self::$base_dir.DIRECTORY_SEPARATOR.'config';
    self::$core_dir           = self::$app_dir.DIRECTORY_SEPARATOR.'Core';
    self::$controller_dir     = self::$app_dir.DIRECTORY_SEPARATOR.'Controllers';
    self::$templates_base_dir = self::$app_dir.DIRECTORY_SEPARATOR.'Templates';
    self::$vendor_dir         = self::$base_dir.DIRECTORY_SEPARATOR.'vendor';
  }
  
  
  /**
   * Initialize the configuration object
   * 
   * @access  private
   * @static
   */
  private static function setup()
  {
    $config_file = self::$config_dir.DIRECTORY_SEPARATOR.'config.php';
    if (file_exists($config_file) === false) {
      throw new Exception('no config file');
    }
    
    include_once $config_file;
    
    Config::add(array(
      'base_dir'            => self::$base_dir,
      'app_dir'             => self::$app_dir,
      'config_dir'          => self::$config_dir,
      'core_dir'            => self::$core_dir,
      'controller_dir'      => self::$controller_dir,
      'templates_base_dir'  => self::$templates_base_dir,
      'templates_dir'       => self::$templates_base_dir.DIRECTORY_SEPARATOR.Config::get('template'),
      'vendor_dir'          => self::$vendor_dir,
    ));
  }
  
  
  /**
   * Register autoloaders
   * 
   * @access  private
   * @static
   */
  private static function autoload()
  {
    // OpenSondage autoloader
    Autoloader::register();
    
    // Propel autoloader
    require_once self::$vendor_dir.DIRECTORY_SEPARATOR.'propel'.DIRECTORY_SEPARATOR.'runtime'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Propel.php';
    \Propel::init(self::$config_dir.DIRECTORY_SEPARATOR.'OpenSondage-conf.php');
    
    // Swift autoloader
    require_once self::$vendor_dir.DIRECTORY_SEPARATOR.'swiftmailer'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'swift_required.php';
    
    // Twig autoloader
    require_once self::$vendor_dir.DIRECTORY_SEPARATOR.'twig'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Twig'.DIRECTORY_SEPARATOR.'Autoloader.php';
    \Twig_Autoloader::register();
  }
  
  
  /**
   * Initialize the request
   * 
   * @access  private
   * @static
   */
  private static function getRouting()
  {
    self::$request = Http\Request::createFromGlobals();
    Http\Routing::current(self::$request);
    
    // Add the arguments found in the routing
    self::$request->query->add(Http\Routing::getArguments());
  }
  
  
  /**
   * Select the good controler according to the request
   * 
   * @access  private
   * @static
   */
  private static function getController()
  {
    $controller = new Controller(self::$request);
    self::$controller = $controller->execute(Http\Routing::getController(), Http\Routing::getAction());
  }
  
  
  /**
   * Render the controler with a view
   * 
   * @access  private
   * @static
   */
  private static function getView()
  {
    $loader = new \Twig_Loader_Filesystem(Config::get('templates_dir'));
    $environnement = array();
    
    if (Config::get('cache_enable') === true) {
      $environnement['cache'] = self::$base_dir.DIRECTORY_SEPARATOR.Config::get('cache_dir');
    }
    
    $twig = new \Twig_Environment($loader, $environnement);
    $twig->addExtension(new Twig\Path());
    $twig->addExtension(new Twig\Tag());
    $twig->addExtension(new Twig\Text());
    
    echo $twig->render(self::$controller->getTemplatePath(), self::$controller->getVars());
  }
}