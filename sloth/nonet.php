<?php defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Factory;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();

// Purge the useless cookie for the front end, it's 2020!
setcookie(Factory::getSession()->getName(), '', [
    'expires' => time() - 3600,
    'path' => $app->get('cookie_path', '/'),
    'domain' => $app->get('cookie_domain'),
    'secure' => true,
    'httponly' => false,
    'samesite' => 'strict',
]);

// Render the page
echo

'<div class="wrapper">',
'<h1>Network is absent, you\'re offline</h1>';
