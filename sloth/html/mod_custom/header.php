<?php
defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

$renderer = Factory::getDocument()->loadRenderer('modules');

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getDocument()->getWebAssetManager();
$wa->useStyle('mod_custom.header');

// The actual page:
echo
'<header>',
    '<div class="header-top">',

      '<a href="' . Uri::root(true) . '" class="logo" title"Home" aria-label="Home" style="width: 147px;height: 56px;border-bottom: 0"><svg xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" clip-rule="evenodd" viewBox="0 0 126 41" fill="currentColor"><path fill-rule="nonzero" d="M4.217 25.3c.45.084.95.169 1.5.211.45.042.95.085 1.5.127.5.043 1 .085 1.55.085.95 0 1.85-.085 2.75-.297.85-.17 1.65-.466 2.35-.89.7-.424 1.25-.975 1.7-1.653.4-.636.6-1.442.6-2.417 0-.805-.25-1.61-.7-2.331-.5-.72-1.1-1.441-1.85-2.12a33.23 33.23 0 00-2.4-1.95 50.345 50.345 0 01-2.4-1.907c-.75-.594-1.4-1.272-1.85-1.908-.5-.594-.75-1.272-.75-1.95 0-1.018.4-1.823 1.15-2.374.75-.551 2-.806 3.7-.806h.85c.25.043.55.043.8.085.25.043.55.043.8.043V4.145c-.25 0-.5 0-.75-.042s-.5-.042-.75-.085h-.9c-1.8 0-3.3.34-4.45 1.018-1.15.72-1.75 1.78-1.75 3.264 0 .805.25 1.526.75 2.204.45.72 1.1 1.4 1.85 2.035a33.23 33.23 0 002.4 1.95c.85.636 1.6 1.23 2.4 1.865.75.636 1.35 1.315 1.85 1.993.45.678.7 1.399.7 2.12 0 1.356-.55 2.373-1.6 3.094-1.1.72-2.6 1.06-4.5 1.06-1.15 0-2.15-.085-3.1-.212-.5-.043-1-.127-1.45-.212v1.102zm-4.2-5.088c.75.212 1.5.34 2.3.466a25.252 25.252 0 004.85.467c1.55 0 2.3-.467 2.3-1.442 0-.381-.25-.763-.7-1.144-.45-.382-1.05-.806-1.75-1.272-.75-.424-1.5-.975-2.3-1.526-.85-.551-1.6-1.23-2.3-1.95a8.686 8.686 0 01-1.8-2.501c-.45-.89-.7-1.95-.7-3.137 0-1.314.3-2.501.85-3.519.55-1.017 1.35-1.865 2.35-2.543 1-.679 2.2-1.187 3.55-1.569 1.35-.339 2.75-.509 4.3-.509.8 0 1.6.043 2.4.085.8.085 1.5.17 2.15.212.75.085 1.45.17 2.15.254v7.928l-1.8-.17c-.35-.042-.7-.042-1.05-.042h-.75c-.3.042-.6.085-.85.127-.25.042-.5.17-.7.297-.2.127-.3.339-.3.593 0 .212.25.509.7.848.45.34 1 .72 1.65 1.187.65.466 1.35.975 2.15 1.569.75.593 1.5 1.271 2.15 2.034.65.763 1.2 1.611 1.65 2.586.45.975.65 2.035.65 3.222 0 1.23-.25 2.417-.7 3.476-.45 1.103-1.2 2.035-2.15 2.84-1 .848-2.25 1.442-3.8 1.95-1.55.467-3.35.679-5.45.679-1.25 0-2.45-.042-3.5-.127-1.1-.127-2.05-.255-2.9-.382-.95-.127-1.85-.297-2.65-.509v-8.478zM23.417.712h11.1v20.857h9.2v7.418h-20.3V.712zm17.5 24.884v-1.018h-11.2V4.103h-1.3v21.493h12.5zM43.842 25.227c0-2.035.45-3.942 1.4-5.765.9-1.823 2.15-3.392 3.75-4.748 1.55-1.314 3.4-2.374 5.55-3.18 2.15-.763 4.4-1.144 6.8-1.144 2.4 0 4.65.381 6.8 1.144 2.15.806 4 1.866 5.6 3.18a16.2 16.2 0 013.75 4.748c.9 1.823 1.35 3.73 1.35 5.765 0 2.035-.45 3.943-1.35 5.765a15.496 15.496 0 01-3.75 4.706c-1.6 1.356-3.45 2.416-5.6 3.18-2.15.805-4.4 1.186-6.8 1.186-2.4 0-4.65-.381-6.8-1.187-2.15-.763-4-1.823-5.55-3.18a14.122 14.122 0 01-3.75-4.705c-.95-1.822-1.4-3.73-1.4-5.765zm4.7 0c0 1.484.35 2.883 1 4.197s1.6 2.501 2.75 3.476c1.15.975 2.55 1.78 4.1 2.332 1.55.55 3.2.847 4.95.847s3.45-.296 5-.847a12.94 12.94 0 004.05-2.332c1.15-.975 2.1-2.162 2.75-3.476.65-1.314 1-2.713 1-4.197s-.35-2.883-1-4.24c-.65-1.313-1.6-2.458-2.75-3.433a12.94 12.94 0 00-4.05-2.331 14.968 14.968 0 00-5-.848c-1.75 0-3.4.296-4.95.848-1.55.55-2.95 1.356-4.1 2.331-1.15.975-2.1 2.12-2.75 3.434-.65 1.356-1 2.755-1 4.24zm5.8 0c0 .806.2 1.569.55 2.29.35.72.85 1.356 1.5 1.907.65.551 1.4.975 2.25 1.272.85.296 1.75.466 2.7.466.95 0 1.9-.17 2.75-.466.85-.297 1.55-.721 2.2-1.272a5.825 5.825 0 001.5-1.908c.35-.72.55-1.483.55-2.289 0-.805-.2-1.61-.55-2.332-.35-.72-.85-1.314-1.5-1.865a6.779 6.779 0 00-2.2-1.272 8.441 8.441 0 00-2.75-.466c-.95 0-1.85.17-2.7.466-.85.297-1.6.721-2.25 1.272-.65.551-1.15 1.145-1.5 1.865a5.38 5.38 0 00-.55 2.332zm7 9.75c-1.6 0-3.1-.254-4.45-.763-1.4-.509-2.6-1.23-3.65-2.12-1.05-.89-1.9-1.907-2.5-3.094-.6-1.145-.9-2.416-.9-3.773 0-1.356.3-2.628.9-3.815.6-1.145 1.45-2.162 2.5-3.095 1.05-.89 2.25-1.568 3.65-2.077 1.35-.509 2.85-.763 4.45-.763s3.1.254 4.5.763a13 13 0 013.65 2.077c1.05.933 1.85 1.95 2.45 3.095.6 1.187.9 2.459.9 3.815 0 1.357-.3 2.628-.9 3.773a9.77 9.77 0 01-2.45 3.095c-1.1.89-2.3 1.61-3.65 2.12-1.4.508-2.9.762-4.5.762z"/><path fill-rule="nonzero" d="M78.914 8.13h-6.15V.712h23.3V8.13h-6.15v20.857h-11V8.13zm6.15 17.466V5.12h7.15V4.103h-15.6V5.12h7.15v20.476h1.3zM97.814.712h10.65v9.623h5.95V.712h10.7v28.275h-10.7v-10.47h-5.95v10.47h-10.65V.712zm6.3 24.884V14.87h14.7v10.725h1.3V4.103h-1.3v9.75h-14.7v-9.75h-1.3v21.493h1.3z"/></svg></a>',

      '<ul class="social">',
        '<li><a href="https://twitter.com/dgrammatiko" title="Twitter"><svg aria-hidden="true" focusable="false" class="social-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-48.9 158.8c.2 2.8.2 5.7.2 8.5 0 86.7-66 186.6-186.6 186.6-37.2 0-71.7-10.8-100.7-29.4 5.3.6 10.4.8 15.8.8 30.7 0 58.9-10.4 81.4-28-28.8-.6-53-19.5-61.3-45.5 10.1 1.5 19.2 1.5 29.6-1.2-30-6.1-52.5-32.5-52.5-64.4v-.8c8.7 4.9 18.9 7.9 29.6 8.3a65.447 65.447 0 0 1-29.2-54.6c0-12.2 3.2-23.4 8.9-33.1 32.3 39.8 80.8 65.8 135.2 68.6-9.3-44.5 24-80.6 64-80.6 18.9 0 35.9 7.9 47.9 20.7 14.8-2.8 29-8.3 41.6-15.8-4.9 15.2-15.2 28-28.8 36.1 13.2-1.4 26-5.1 37.8-10.2-8.9 13.1-20.1 24.7-32.9 34z"></path></svg></a></li>',
        '<li><a href="https://github.com/dgrammatiko" title="Github"><svg aria-hidden="true" focusable="false" class="social-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM277.3 415.7c-8.4 1.5-11.5-3.7-11.5-8 0-5.4.2-33 .2-55.3 0-15.6-5.2-25.5-11.3-30.7 37-4.1 76-9.2 76-73.1 0-18.2-6.5-27.3-17.1-39 1.7-4.3 7.4-22-1.7-45-13.9-4.3-45.7 17.9-45.7 17.9-13.2-3.7-27.5-5.6-41.6-5.6-14.1 0-28.4 1.9-41.6 5.6 0 0-31.8-22.2-45.7-17.9-9.1 22.9-3.5 40.6-1.7 45-10.6 11.7-15.6 20.8-15.6 39 0 63.6 37.3 69 74.3 73.1-4.8 4.3-9.1 11.7-10.6 22.3-9.5 4.3-33.8 11.7-48.3-13.9-9.1-15.8-25.5-17.1-25.5-17.1-16.2-.2-1.1 10.2-1.1 10.2 10.8 5 18.4 24.2 18.4 24.2 9.7 29.7 56.1 19.7 56.1 19.7 0 13.9.2 36.5.2 40.6 0 4.3-3 9.5-11.5 8-66-22.1-112.2-84.9-112.2-158.3 0-91.8 70.2-161.5 162-161.5S388 165.6 388 257.4c.1 73.4-44.7 136.3-110.7 158.3zm-98.1-61.1c-1.9.4-3.7-.4-3.9-1.7-.2-1.5 1.1-2.8 3-3.2 1.9-.2 3.7.6 3.9 1.9.3 1.3-1 2.6-3 3zm-9.5-.9c0 1.3-1.5 2.4-3.5 2.4-2.2.2-3.7-.9-3.7-2.4 0-1.3 1.5-2.4 3.5-2.4 1.9-.2 3.7.9 3.7 2.4zm-13.7-1.1c-.4 1.3-2.4 1.9-4.1 1.3-1.9-.4-3.2-1.9-2.8-3.2.4-1.3 2.4-1.9 4.1-1.5 2 .6 3.3 2.1 2.8 3.4zm-12.3-5.4c-.9 1.1-2.8.9-4.3-.6-1.5-1.3-1.9-3.2-.9-4.1.9-1.1 2.8-.9 4.3.6 1.3 1.3 1.8 3.3.9 4.1zm-9.1-9.1c-.9.6-2.6 0-3.7-1.5s-1.1-3.2 0-3.9c1.1-.9 2.8-.2 3.7 1.3 1.1 1.5 1.1 3.3 0 4.1zm-6.5-9.7c-.9.9-2.4.4-3.5-.6-1.1-1.3-1.3-2.8-.4-3.5.9-.9 2.4-.4 3.5.6 1.1 1.3 1.3 2.8.4 3.5zm-6.7-7.4c-.4.9-1.7 1.1-2.8.4-1.3-.6-1.9-1.7-1.5-2.6.4-.6 1.5-.9 2.8-.4 1.3.7 1.9 1.8 1.5 2.6z"></path></svg></a></li>',
      '</ul>',

      '<button type="button" class="burger" aria-expanded="false">',
        '<span aria-hidden="true">☰</span> Menu',
      '</button>',

    '</div>',

	  $renderer->render('menu', array('style' => 'raw', 'layout' => 'header'), null),

    '<div class="overlay"></div>',
'</header>';
