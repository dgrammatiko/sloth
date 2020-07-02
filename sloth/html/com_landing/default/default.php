<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;

$document = Factory::getDocument();
$renderer = $document->loadRenderer('modules');

// Alternative method
$document->setTitle('Welcome to Sloth, the fastest Joomla 4 template');
$document->setDescription('Sloth, an SPA/PWA template for Joomla 4. Also the fastest one!');

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getDocument()->getWebAssetManager();
$wa->useStyle('com_landing.default');

// The actual page:
echo
    $renderer->render('frontpageup', array('style' => 'raw'), null),
    $renderer->render('frontpagedown', array('style' => 'raw'), null);
