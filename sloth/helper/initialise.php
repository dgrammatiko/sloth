<?php
use Joomla\CMS\HTML\HTMLHelper;

/** @var JDocumentHtml $this */

// Remove the tabs+new line
$this->setLineEnd('');
$this->setTab('');

// Set custom Generator
//$this->setGenerator('Whatever');

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'sloth-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);

$this->setMetaData('description', 'width=device-width, initial-scale=1');
$this->addHeadLink(HTMLHelper::_('script', 'site.json', ['relative' => true, 'version' => 'auto'], []), '', 'manifest', []);
$this->addHeadLink(HTMLHelper::_('image', 'apple-touch-icon.png', '', [], true, 1), '', 'apple-touch-icon', ['type' => 'image/png', 'sizes' => '180x180']);

include_once JPATH_THEMES .'/sloth/helper/SlothmetasRenderer.php';
include_once JPATH_THEMES .'/sloth/helper/SlothstylesRenderer.php';
include_once JPATH_THEMES .'/sloth/helper/SlothscriptsRenderer.php';
