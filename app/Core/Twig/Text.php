<?php

namespace OpenSondage\Core\Twig;

/**
 * This file is part of Twig.
 * -----> Modified by Simon Leblanc for OpenSondage
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Henrik Bjornskov <hb@peytz.dk>
 * @package Twig
 * @subpackage Twig-extensions
 */
class Text extends \Twig_Extension
{
    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'nl2br' => new \Twig_Filter_Method($this, 'nl2br', array('pre_escape' => 'html', 'is_safe' => array('html'))),
            'cid'   => new \Twig_Filter_Method($this, 'nameToId', array('pre_escape' => 'html', 'is_safe' => array('html'))),
        );
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'Text';
    }
    
    public function nl2br($value, $sep = '<br />')
    {
      return str_replace("\n", $sep."\n", $value);
    }
    
    public function nameToId($name)
    {
      return preg_replace('/[^a-z0-9_-]/i', '_', $name);
    }
}