<?php
defined('_JEXEC') || die;

use Joomla\CMS\Factory;

$component = $this->getBuffer('component');
$app = Factory::getApplication();

setcookie(
  Factory::getSession()->getName(),
  '',
  time() - 3600, $app->get('cookie_path', '/'),
  $app->get('cookie_domain')
);

echo $component . '</script>document.title="' . htmlspecialchars($this->_doc->getTitle(), ENT_COMPAT, 'UTF-8') . '"</script>';
