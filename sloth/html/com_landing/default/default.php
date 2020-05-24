<?php
defined('_JEXEC') || die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

$document = Factory::getDocument();
$renderer = $document->loadRenderer('modules');

// Alternative method
$document->setTitle('Welcome to Sloth, the fastest Joomla 4 template');
$document->setDescription('Sloth, an SPA/PWA template for Joomla 4. Also the fastest one!');

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('landing_css', 'com_landing/default.css', ['relative' => true, 'version' => 'auto'], []);


// The actual page:
echo
    $renderer->render('frontpageup', array('style' => 'raw'), null),
    $renderer->render('frontpagedown', array('style' => 'raw'), null);
