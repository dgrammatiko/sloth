<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getDocument()->getWebAssetManager();
//$wa->registerAndUseStyle('landing_css', 'com_landing/default.css', ['relative' => true, 'version' => '1.0.0'], ['inline' => true]);
$wa->useStyle('com_content.category_blog');

if (!count($this->items)) {
  return;
}

$hasResponsiveImages = PluginHelper::isEnabled('content', 'responsive');
if ($hasResponsiveImages) {
  JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php');
}

echo

'<section>',
'<header class="major">',
  '<h2 class="g-center">' . $this->category->title . '</h2>',
'</header>';

echo '<div class="posts">';

for ($i = 0; $i < count($this->items); $i++) {
  $firstImages = json_decode($this->items[$i]->images);
  $image = '';

  if (!empty($firstImages->image_intro)) {
    $image = '<img src="' . $firstImages->image_intro . '" alt="' . $firstImages->image_intro_alt .'" />';
    if ($hasResponsiveImages) {
      $image = (new \Ttc\Freebies\Responsive\Helper)->transformImage($image, [200, 320]);
    }
  }
  echo
  '<article>',
    '<a href="' . Route::_(RouteHelper::getArticleRoute($this->items[$i]->slug, $this->items[$i]->catid, $this->items[$i]->language)) . '" class="image">' . $image . '</a>',
    '<h3>' . $this->items[$i]->title . '</h3>',
    $this->items[$i]->introtext,
    '<a href="' . Route::_(RouteHelper::getArticleRoute($this->items[$i]->slug, $this->items[$i]->catid, $this->items[$i]->language)) . '" class="button">' . $this->items[$i]->title . '</a>',
  '</article>';
}

echo '</div>',
'</section>';
