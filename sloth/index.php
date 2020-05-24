<?php
defined('_JEXEC') || die;

use Joomla\CMS\Factory;

include_once __DIR__ . '/helper/SlothmetasRenderer.php';
include_once __DIR__ . '/helper/SlothstylesRenderer.php';
include_once __DIR__ . '/helper/SlothscriptsRenderer.php';

/** @var Joomla\CMS\Document\HtmlDocument $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getWebAssetManager();
$wa->registerAndUseStyle('sloth_base_css', 'templates/sloth/css/base.min.css', [], []);

$header    = $this->getBuffer('modules', 'header', $attribs = array());
$component = $this->getBuffer('component');

$metas     = $this->getBuffer('Slothmetas');
$styles    = $this->getBuffer('Slothstyles');
$scripts   = $this->getBuffer('Slothscripts');

$app = Factory::getApplication();
//$app->input->cookie->set($app->getSession()->getName(), false, ['expires' => time() - 42000, 'path' => $app->get('cookie_path', '/'), 'domain' => $app->get('cookie_domain')]);

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
      '<div class="inner">',
        $component,
      '</div>',
    '</main>',
  '</body>',
'</html>';
