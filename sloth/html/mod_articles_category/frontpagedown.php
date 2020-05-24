<?php
defined('_JEXEC') || die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;

if (!$list)
{
	return;
}

echo

'<section>',
'<header class="major">',
	'<h2>' . $list[0]->title . '</h2>',
'</header>',
'<div class="posts">';

for ($i = 0; $i < count($list); $i++) {
	$firstImages = json_decode($list[$i]->images);
	$image = '';

	if (!empty($firstImages->image_intro)) {
		$image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" />';
		if (PluginHelper::isEnabled('content', 'responsive')) {
			JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php', true);
			$helper = new \Ttc\Freebies\Responsive\Helper;
			$image = $helper->transformImage($image, [200, 320]);
		}
	}
	echo
	'<article>',
		'<a href="' . $list[$i]->link . '" title="' . $list[$i]->title . '" class="image">' . $image . '</a>',
		'<h3>' . $list[$i]->title . '</h3>',
		'<p>' . $list[$i]->introtext . '</p>',
		'<a href="' . $list[$i]->link . '" title="' . $list[$i]->title . '" class="button">More</a>',
	'</article>';
}

echo '</div>',
'</section>';
