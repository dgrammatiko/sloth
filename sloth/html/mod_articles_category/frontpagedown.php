<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Plugin\PluginHelper;

if (!$list) {
  return;
}

$hasResponsiveImages = PluginHelper::isEnabled('content', 'responsive');
if ($hasResponsiveImages) {
  JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php');
}

echo

'<section>',
'<header class="g-center">',
  '<h2>' . $list[0]->title . '</h2>',
'</header>',
'<div class="posts">';

for ($i = 0; $i < count($list); $i++) {
  $firstImages = json_decode($list[$i]->images);
  $image = '';

  if (!empty($firstImages->image_intro)) {
    $image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" />';
    if ($hasResponsiveImages) {
      $image = (new \Ttc\Freebies\Responsive\Helper)->transformImage($image, [200, 320]);
    }
  }

  echo
  '<article class="box">',
    '<a href="' . $list[$i]->link . '" title="' . $list[$i]->title . '">' . $image . '</a>',
    '<h3><a href="' . $list[$i]->link . '" title="' . $list[$i]->title . '">' . $list[$i]->title  . '</a></h3>',
    $list[$i]->introtext,
//    '<a href="' . $list[$i]->link . '" title="' . $list[$i]->title . '">' . $list[$i]->title . '</a>',
  '</article>';
}

echo '</div>',
'</section>';
