// Serviceworkers file. This code gets installed in users browsers and runs code before the request is made.
const staticCacheName = 'sloth-{{version}}';
const expectedCaches = [
  staticCacheName
];

self.addEventListener('install', event => {
  self.skipWaiting();

  // iOS has issues
  if (/iPhone|CriOS|iPad/i.test(navigator.userAgent)) {
    return;
  }

  // Populate initial serviceworker cache.
  event.waitUntil(
    caches.open(staticCacheName)
      .then(cache => cache.addAll([
        '/?tmpl=header', // head, top bar, inline styles
        '/?tmpl=footer', // footer
        '/?tmpl=400', // Not found page
        '/?tmpl=500', // Error page
        '/?tmpl=nonet' //Offline page
      ]))
  );
});

// remove caches that aren't in expectedCaches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(key => {
        if (!expectedCaches.includes(key)) return caches.delete(key);
      })
    ))
  );
});

// Create a composed streamed webpage with shell and core content
function createPageStream(request) {
  const stream = new ReadableStream({
    start(controller) {
      Promise.all([caches.match('/?tmpl=header'), caches.match('/?tmpl=footer')])
        .then((cachedShellMatches) => {
          const cachedShellTop = cachedShellMatches[0];
          const cachedShellBottom = cachedShellMatches[1];
          if (!cachedShellTop || !cachedShellBottom) { // return if shell isn't cached.
            return;
          }
          // the body url is the request url plus 'include'
          const url = new URL(request.url);
          url.searchParams.set('tmpl', 'cmpt'); // Adds ?tmpl=cmpt or &tmpl=cmpt, which is our indicator for "internal" partial page
          const startFetch = Promise.resolve(cachedShellTop);
          const endFetch = Promise.resolve(cachedShellBottom);
          const middleFetch = fetch(url).then(response => {
            if (!response.ok && response.status === 404) {
              return caches.match('/?tmpl=404');
            }
            if (!response.ok && response.status != 404) {
              return caches.match('/?tmpl=500');
            }
            return response;
          }).catch(err => caches.match('/?tmpl=nonet'));

          function pushStream(stream) {
            const reader = stream.getReader();
            return reader.read().then(function process(result) {
              if (result.done) return;
              controller.enqueue(result.value);
              return reader.read().then(process);
            });
          }
          startFetch
            .then(response => pushStream(response.body))
            .then(() => middleFetch)
            .then(response => pushStream(response.body))
            .then(() => endFetch)
            .then(response => pushStream(response.body))
            .then(() => controller.close());
        })

    }
  });

  return new Response(stream, {
    headers: {'Content-Type': 'text/html; charset=utf-8'}
  });
}

self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  if (/iPhone|CriOS|iPad/i.test(navigator.userAgent)) {
    // iOS seems to have issues.
    return;
  }
  if (url.origin === location.origin) {
    if (event.clientId === "" && // Not fetched via AJAX after page load.
      event.request.method == "GET" && // Don't fetch on POST, DELETE, etc.
      !url.href.includes('tmpl=cmpt') && // Parameter representing "internal" navigation.
      !url.href.includes('.css') && // Don't run on CSS.
      !url.href.includes('.js') && // Don't run on JS.
      !url.href.includes('/administrator') && // Don't fetch for administrate dashboard.
      !url.href.includes('/api/') && // Don't run on API endpoints.
      !url.href.includes('/feed') && // Skip the RSS feed
      !url.href.includes('/rss') && // Skip the RSS feed alternative path
      !url.href.includes('/robots.txt') && // Skip robots for web crawlers
      !url.href.includes('/?tmpl=header') && // Don't fetch for shell.
      !url.href.includes('/?tmpl=footer') && // Don't fetch for shell.

      caches.match('/?tmpl=header') && // Ensure header is in the cache.
      caches.match('/?tmpl=footer')) { // Ensure footer is in the cache.
      event.respondWith(createPageStream(event.request)); // Respond with the stream

      return;
    }
  }
});
