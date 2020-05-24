<?php
defined('_JEXEC') || die;

use Joomla\CMS\Factory;

/** @var Joomla\CMS\Document\HtmlDocument $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getWebAssetManager();
$wa->registerAndUseStyle('sloth_base_css', 'templates/sloth/css/base.min.css', [], []);

$header    = $this->getBuffer('modules', 'header', $attribs = array());
$component = $this->getBuffer('component');

$metas     = $this->getBuffer('metas');
$styles    = $this->getBuffer('styles');
$scripts   = $this->getBuffer('scripts');

$app = Factory::getApplication();

setcookie(
  Factory::getSession()->getName(),
  '',
  time() - 3600, $app->get('cookie_path', '/'),
  $app->get('cookie_domain')
);
echo
'<!doctype html>',
'<html xml:lang="en">',
  '<head>',
    $metas,
    $styles,
    $scripts,
  '</head>',
  '<body>',
    $header,
    '<main>',
      '<div class="inner">';
