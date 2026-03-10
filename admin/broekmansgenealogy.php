<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$booted = Factory::getApplication()
    ->bootComponent('com_broekmansgenealogy');

$booted->getDispatcher()->dispatch();
