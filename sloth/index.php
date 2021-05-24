<?php defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/** @var Joomla\CMS\Document\HtmlDocument $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */

// Override the default Renderers
include_once JPATH_THEMES .'/sloth/helper/SlothmetasRenderer.php';
include_once JPATH_THEMES .'/sloth/helper/SlothstylesRenderer.php';
include_once JPATH_THEMES .'/sloth/helper/SlothscriptsRenderer.php';

$app = Factory::getApplication();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'sloth-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);

/**
 * Register the template assets Either:
 * $this->getWebAssetManager()->registerAndUseStyle('sloth_base_css', 'base.min.css', ['relative' => true, 'version' => '1.0.0'], ['inline' => true]);
 * or:
 */
$this->getWebAssetManager()->useStyle('template.base');

// Purge the useless cookie for the front end, no cookie consent modal!
setcookie(Factory::getSession()->getName(), '', [
    'expires' => time() - 3600,
    'path' => $app->get('cookie_path', '/'),
    'domain' => $app->get('cookie_domain'),
    'secure' => true,
    'httponly' => false,
    'samesite' => 'strict',
]);

$this->getWebAssetManager()->addInlineScript('if ("serviceWorker" in navigator) { window.addEventListener("load", function(){ navigator.serviceWorker.register("/sw.min.js"); }); }', [], ['type' => 'module']);

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
