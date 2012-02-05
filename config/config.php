<?php

$config = array(
  // options for twig
  'template'      => 'framasoft',   // name of the subfolder in app/templates
  'cache_enable'  => false,          // true to enable the cache, false else
  'cache_dir'     => 'cache',       // name of the cache directory
  'compress'      => false,
  
  // statistics
  'stats_enable'  => true,          // true to enable statistics in the web page, false else
  'stats_account' => '',            // the google analytics account, if you don't use GA, change stats.twig.html template
  
  // Propel
  'model_dir'     => __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'classes',
  
  // App options
  'default_nb_question' => 10,
  'locale'              => 'fr_FR',
  'website_name'        => 'OpenSondage',
  'website_logo'        => 'logo/logo-framadate.png',
);

OpenSondage\Core\Config::add($config);


// Include the routing
require_once __DIR__.DIRECTORY_SEPARATOR.'routing.php';