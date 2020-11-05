<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();

// Purge the useless cookie for the front end, it's 2020!
//$app->input->cookie->set($app->getSession()->getName(), false, ['expires' => time() - 42000, 'path' => $app->get('cookie_path', '/'), 'domain' => $app->get('cookie_domain')]);
setcookie(
  Factory::getSession()->getName(),
  '',
  time() - 3600, $app->get('cookie_path', '/'),
  $app->get('cookie_domain')
);

// Render the page
echo

'<div class="wrapper">',
'<h1>This wasn\'t expected, 404</h1>',
'</div>';
