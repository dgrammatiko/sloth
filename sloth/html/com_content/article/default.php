<?php
defined('_JEXEC') || die;

$images = json_decode($this->item->images);
$image = '';

if (!empty($images->image_intro)) {
  $image = '<img src="' . $images->image_intro . '" alt="' . $images->image_intro_alt .'" style="width: 100% !important;"/>';
  if (\Joomla\CMS\Plugin\PluginHelper::isEnabled('content', 'responsive')) {
    JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php', true);
    $helper = new \Ttc\Freebies\Responsive\Helper;
    // 200, 320, 480, 768, 992, 1200, 1600, 1920
    $image = $helper->transformImage($image, [200, 320, 480, 768, 992, 1200, 1600, 1920]);
  }
}


echo
$image,
'<section style="margin: 0 auto; max-width: 80ch">',
  '<header class="main">',
    '<h1>' . $this->escape($this->item->title) . '</h1>',
  $this->item->event->afterDisplayTitle,
  '</header>',
  $this->item->event->beforeDisplayContent,
  $this->item->introtext,
  $this->item->fulltext,
  $this->item->event->afterDisplayContent,
'</section>';
