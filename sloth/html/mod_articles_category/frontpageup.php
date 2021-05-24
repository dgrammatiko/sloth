<?php defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Plugin\PluginHelper;

if (!$list) {
  return;
}

$hasResponsiveImages = PluginHelper::isEnabled('content', 'responsive');
if ($hasResponsiveImages) {
  JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php');
}

$firstImages = json_decode($list[0]->images);

$image = '';

if (!empty($firstImages->image_intro)) {
  $image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" />';
  if ($hasResponsiveImages) {
    $image = (new \Ttc\Freebies\Responsive\Helper)->transformImage($image, [200, 320, 480]);
  }
}

echo
'<section class="cols-2">',
  '<div class="content">',
    '<header>',
      '<h1>' . $list[0]->title . '</h1>',
    '</header>',
    '<p>' . $list[0]->introtext . '</p>',
    '<a class="button" href="' . $list[0]->link . '">' . $list[0]->title . '</a>',
  '</div>',
  '<span class="image object">',
    $image,
  '</span>',
'</section>';

echo
'<section>',
  '<header class="g-center">',
    '<h2>' . $list[0]->category_title . '</h2>',
  '</header>',
  '<div class="cols-2">';

for ($i = 1; $i < count($list); $i++) {
  $firstImages = json_decode($list[$i]->images);
  $image = '';

  if (!empty($firstImages->image_intro)) {
    $image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" />';
    if ($hasResponsiveImages) {
      $image = (new \Ttc\Freebies\Responsive\Helper)->transformImage($image, [200, 320, 480]);
    }
  }


  echo
  '<article class="features">',
    '<span class="image">' . $image . '</span>',
    '<div class="content">',
      '<h3><a href="' . $list[$i]->link . '">' . $list[$i]->title . '</a></h3>',
      $list[$i]->introtext,
    '</div>',
  '</article>';
}

echo '</div>',
'</section>';
