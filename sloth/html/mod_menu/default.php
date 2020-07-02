<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Helper\ModuleHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
//$wa->registerAndUseScript('mod_menu_js', 'mod_menu/default.min.js', ['relative' => true, 'version' => '1.0.0'], ['defer' => true, 'type' => 'module']);
//$wa->registerAndUseStyle('mod_menu_css', 'mod_menu/default.min.css', ['relative' => true, 'version' => '1.0.0'], ['inline' => true]);

$wa->useScript('mod_menu.default');
$wa->useStyle('mod_menu.default');

$tagId = $params->get('tag_id', '');
$id = empty($tagId) ? '' : ' id="' . $tagId . '"';

echo

'<nav class="navbar main" style="display: none">',
  '<button type="button" class="navClose">',
  'Close  <span aria-hidden="true" style="font-size: 1.2rem">ⓧ</span>',
  '</button>',
  '<ul class="nav-links">';

foreach ($list as $i => &$item)  {
	$itemParams = $item->getParams();
	$class      = 'nav-item item-' . $item->id;

	if ($item->id == $default_id) {
		$class .= ' default';
	}

	if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	} elseif ($item->type === 'alias') {
		$aliasToId = $itemParams->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
			$class .= ' active';
		} elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type === 'separator') {
		$class .= ' divider';
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent) {
		$class .= ' parent';
	}

	echo '<li class="' . $class . '">';

	switch ($item->type) :
		case 'separator':
		case 'component':
		case 'heading':
		case 'url':
			require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
			break;

		default:
			require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul class="mod-menu__sub list-unstyled small">';
	} elseif ($item->shallower) {
		// The next item is shallower.
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	} else {
		// The next item is on the same level.
		echo '</li>';
	}
}
echo '</ul></nav>';
