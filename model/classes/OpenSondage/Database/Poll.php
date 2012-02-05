<?php

namespace OpenSondage\Database;

use OpenSondage\Database\om\BasePoll;


/**
 * Skeleton subclass for representing a row from the 'poll' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.OpenSondage.Database
 */
class Poll extends BasePoll
{
  public function initUid()
  {
    $uid = sha1(uniqid(rand(0, 999999), true).microtime());
    
    $this->setPrivateUid(substr($uid, 0, 20));
    $this->setPublicUid(substr($uid, 0, 12));
  }
} // Poll
