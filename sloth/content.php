<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;

$component = $this->getBuffer('component');
$app = Factory::getApplication();
$title = htmlspecialchars($this->_doc->getTitle(), ENT_COMPAT, 'UTF-8');
$description = htmlspecialchars($this->_doc->getDescription(), ENT_COMPAT, 'UTF-8');
setcookie(
  Factory::getSession()->getName(),
  '',
  time() - 3600, $app->get('cookie_path', '/'),
  $app->get('cookie_domain')
);

echo $component . '</script>' .
  'document.title="' . $title . '";'.
  /**
   * The following line is totally useless as the description is only used by machines/bots...
   * It was added only to showcase the ability to update any of the head tags
   */
  'document.head.querySelector(\'[name="description"]\').setAttribute("content", "' . $description . '");'.
  '</script>';
