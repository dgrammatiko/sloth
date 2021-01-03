<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getDocument()->getWebAssetManager();
//$wa->registerAndUseStyle('landing_css', 'com_landing/default.css', ['relative' => true, 'version' => '1.0.0'], ['inline' => true]);
$wa->useStyle('mod_footer.default');

echo
'<footer class="main">',
  '<div class="items">Sloth is a free joomla4  PWA template</div>',
  '<div class="items">Copyright Ⓒ 2019-' . date("Y") . '  <a rel="external" href="https://dgrammatiko.online">D. Grammatikogiannis</a></div>',
'</footer>';
