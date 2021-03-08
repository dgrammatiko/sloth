<?php defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

setcookie(Factory::getSession()->getName(), '', [
    'expires' => time() - 3600,
    'path' => $app->get('cookie_path', '/'),
    'domain' => $app->get('cookie_domain'),
    'secure' => true,
    'httponly' => false,
    'samesite' => 'strict',
]);

echo
      '</div>',
    '</main>',
    $this->getBuffer('modules', 'footer', []),
  '</body>',
'</html>';
