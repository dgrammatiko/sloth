<?php defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$component   = $this->getBuffer('component');
$app         = Factory::getApplication();
$title       = htmlspecialchars($this->title, ENT_COMPAT, 'UTF-8');
$description = htmlspecialchars($this->description, ENT_COMPAT, 'UTF-8');

setcookie(Factory::getSession()->getName(), '', [
    'expires' => time() - 3600,
    'path' => $app->get('cookie_path', '/'),
    'domain' => $app->get('cookie_domain'),
    'secure' => true,
    'httponly' => false,
    'samesite' => 'strict',
]);

echo $component . '<script>' .
  'document.title="' . $title . '";'.
  /**
   * The following line is totally useless as the description is only used by machines/bots...
   * It was added only to showcase the ability to update any of the head tags
   */
  'document.head.querySelector(\'[name="description"]\').setAttribute("content", "' . $description . '");'.
  '</script>';
