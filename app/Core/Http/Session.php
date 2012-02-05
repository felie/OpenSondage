<?php
namespace OpenSondage\Core\Http;

use Symfony\Component\HttpFoundation\SessionStorage\SessionStorageInterface;


/**
 * Session class
 *
 * @package     OpenSondage\Core
 * @subpackage  OpenSondage\Core\Http
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Session extends \Symfony\Component\HttpFoundation\Session
{
  /**
   * Constructor.
   *
   * @param SessionStorageInterface $storage A SessionStorageInterface instance
   */
  public function __construct(SessionStorageInterface $storage)
  {
    parent::__construct($storage);
  }
}