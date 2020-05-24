<?php
defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('landing_css', 'com_landing/default.css', ['relative' => true, 'version' => 'auto'], []);

if (!count($this->items)) {
  return;
}

$firstImages = json_decode($this->items[0]->images);

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

'<section>',
'<header class="major">',
  '<h2>' . $this->items[0]->title . '</h2>',
'</header>';

echo '<div class="posts">';

for ($i = 0; $i < count($this->items); $i++) {
  $firstImages = json_decode($this->items[$i]->images);
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
    '<a href="' . $this->items[$i]->link . '" class="image">' . $image . '</a>',
    '<h3>' . $this->items[$i]->title . '</h3>',
    '<p>' . $this->items[$i]->introtext . '</p>',
    '<a href="' . $this->items[$i]->link . '" class="button">More</a>',
  '</article>';
}

echo '</div>',
'</section>';
