<?php

OpenSondage\Core\Http\Routing::add(array(
  '/' => array(
    'controller'  => 'Index',
    'action'      => 'Default'),
  
  '/about' => array(
    'controller'  => 'Index',
    'action'      => 'About'),
  
  '/contact' => array(
    'controller'  => 'Index',
    'action'      => 'Contact'),
  
  '/contact/sended' => array(
    'controller'  => 'Index',
    'action'      => 'Sended'),
  
  '/poll/:public_uid' => array(
    'controller'  => 'Poll',
    'action'      => 'Default'),
  
  '/poll/:public_uid/edit/:user_id' => array(
    'controller'  => 'Poll',
    'action'      => 'Default'),
  
  '/poll/response/:public_uid' => array(
    'controller'  => 'Poll',
    'action'      => 'Response'),
  
  '/poll/admin/:private_uid' => array(
    'controller'  => 'Poll',
    'action'      => 'Admin'),
  
  '/create' => array(
    'controller'  => 'Edit',
    'action'      => 'Default'),
  
  '/create/poll' => array(
    'controller'  => 'Edit',
    'action'      => 'CreatePoll'),
  
  '/create/meeting' => array(
    'controller'  => 'Edit',
    'action'      => 'CreateMeeting'),
));
