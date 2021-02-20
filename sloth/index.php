<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/** @var Joomla\CMS\Document\HtmlDocument $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */

// Override the default Renderers
include_once __DIR__ . '/helper/SlothmetasRenderer.php';
include_once __DIR__ . '/helper/SlothstylesRenderer.php';
include_once __DIR__ . '/helper/SlothscriptsRenderer.php';

$app = Factory::getApplication();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'sloth-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'sloth-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

/**
 * Register the template assets Either:
 * $this->getWebAssetManager()->registerAndUseStyle('sloth_base_css', 'base.min.css', ['relative' => true, 'version' => '1.0.0'], ['inline' => true]);
 * or:
 */
$this->getWebAssetManager()->useStyle('template.base');

// Purge the useless cookie for the front end, no cookie consent modal!
//$app->input->cookie->set($app->getSession()->getName(), false, ['expires' => time() - 42000, 'path' => $app->get('cookie_path', '/'), 'domain' => $app->get('cookie_domain')]);
setcookie(
  Factory::getSession()->getName(),
  '',
  time() - 3600, $app->get('cookie_path', '/'),
  $app->get('cookie_domain')
);

$this->getWebAssetManager()->addInlineScript('if ("serviceWorker" in navigator) { window.addEventListener("load", () => { navigator.serviceWorker.register("/sw.min.js"); }); }', [], ['type' => 'module']);

// Get the output for all the template sections
$component = $this->getBuffer('component');
$header    = $this->getBuffer('modules', 'header', []);
$footer    = $this->getBuffer('modules', 'footer', []);
$metas     = $this->getBuffer('Slothmetas');
$styles    = $this->getBuffer('Slothstyles');
$scripts   = $this->getBuffer('Slothscripts');

// Render the page
echo
'<!doctype html>',
'<html lang="' . $this->language . '">',
  '<head>',
    $metas,
    $styles,
    $scripts,
  '</head>',
  '<body>',
    $header,
    '<main>',
      '<div class="wrapper">',
        $component,
      '</div>',
    '</main>',
    $footer,
  '</body>',
'</html>';
