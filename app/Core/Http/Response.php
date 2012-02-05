<?php
namespace OpenSondage\Core\Http;


/**
 * Response class
 *
 * @package     OpenSondage\Core
 * @subpackage  OpenSondage\Core\Http
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     https://www.gnu.org/licenses/agpl.html GNU/AGPL v3
 */
class Response extends \Symfony\Component\HttpFoundation\Response
{
  public function redirect($url, $status = 302)
  {
    if (strpos($url, 'http') === false) {
      $request = Request::createFromGlobals();
      $url = $request->getUriForPath($request->getUrlFor($url));
    }
    
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($url, $status);
    $response->send();
  }
  
}