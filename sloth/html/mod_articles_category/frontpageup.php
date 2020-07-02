<?php
defined('_JEXEC') || die;

use Joomla\CMS\Plugin\PluginHelper;

if (!$list) {
	return;
}

$firstImages = json_decode($list[0]->images);

$image = '';

if (!empty($firstImages->image_intro)) {
	$image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" />';
	if (PluginHelper::isEnabled('content', 'responsive')) {
		JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php', true);
		$helper = new \Ttc\Freebies\Responsive\Helper;
		$image = $helper->transformImage($image, [200, 320, 480, 768,]);
	}
}

echo
'<section class="cols-2">',
  '<div class="content">',
    '<header>',
      '<h1>' . $list[0]->title . '</h1>',
    '</header>',
    '<p>' . $list[0]->introtext . '</p>',
    '<a href="' . $list[0]->link . '" class="button">' . $list[0]->title . '</a>',
  '</div>',
  '<span class="image object">',
    $image,
  '</span>',
'</section>';

echo
'<section>',
	'<header class="major">',
		'<h2>' . $list[0]->category_title . '</h2>',
	'</header>',
	'<div class="features">';

for ($i = 1; $i < count($list); $i++) {
  $firstImages = json_decode($list[$i]->images);
  $image = '';

  if (!empty($firstImages->image_intro)) {
    $image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" style="width: 200px; height: 200px; clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);"/>';
    if (PluginHelper::isEnabled('content', 'responsive')) {
      JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php', true);
      $helper = new \Ttc\Freebies\Responsive\Helper;
      $image = $helper->transformImage($image, [200, 320, 480, 640, 700]);
    }
  }


  echo
  '<article class="cols-2">',
    '<span class="image" style="min-width: 210px">' . $image . '</span>',
    '<div class="content">',
      '<h3><a role="button" href="' . $list[$i]->link . '"  class="button">' . $list[$i]->title . '</a></h3>',
      '<p>' . $list[$i]->introtext . '</p>',
    '</div>',
  '</article>';
}

echo '</div>',
'</section>';
