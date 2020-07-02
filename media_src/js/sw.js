// Serviceworkers file. This code gets installed in users browsers and runs code before the request is made.
const staticCacheName = 'static-{{version}}';
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
        "/shell_top", // head, top bar, inline styles
        "/shell_bottom", // footer
        "/404.html", // Not found page
        "/500.html", // Error page
        "/offline.html" //Offline page
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
      Promise.all([caches.match('/shell_top'), caches.match('/shell_bottom')])
        .then((cachedShellMatches) => {
          const cachedShellTop = cachedShellMatches[0];
          const cachedShellBottom = cachedShellMatches[1];
          if (!cachedShellTop || !cachedShellBottom) { // return if shell isn't cached.
            return;
          }
          // the body url is the request url plus 'include'
          const url = new URL(request.url);
          url.searchParams.set('i', 'i'); // Adds ?i=i or &i=i, which is our indicator for "internal" partial page
          const startFetch = Promise.resolve(cachedShellTop);
          const endFetch = Promise.resolve(cachedShellBottom);
          const middleFetch = fetch(url).then(response => {
            if (!response.ok && response.status === 404) {
              return caches.match('/404.html');
            }
            if (!response.ok && response.status != 404) {
              return caches.match('/500.html');
            }
            return response;
          }).catch(err => caches.match('/offline.html'));

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
      !event.request.referrer.includes('/signout_confirm') && // If this is the referrer, we instead want to flush.

      url.pathname !== '/new' && // We have no shell for /new

      !url.href.includes('i=i') && // Parameter representing "internal" navigation.
      !url.href.includes('.css') && // Don't run on CSS.
      !url.href.includes('.js') && // Don't run on JS.
      !url.href.includes('?preview=') && // Skip for preview pages.
      !url.href.includes('?signin') && // Don't run on sign in.

      !url.href.includes('/admin') && // Don't fetch for administrate dashboard.
      !url.href.includes('/api/') && // Don't run on API endpoints.
      !url.href.includes('/embed/') && // Don't fetch for embeded content.
      !url.href.includes('/feed') && // Skip the RSS feed
      !url.href.includes('/rss') && // Skip the RSS feed alternative path
      !url.href.includes('/future') && // Skip for /future.
      !url.href.includes('/internal') && // Don't fetch for internal dashboard.
      !url.href.includes('/oauth/') && // Skip oauth apps
      !url.href.includes('/onboarding') && // Don't run on onboarding.
      !url.href.includes('/rails/mailers') && // Skip for mailers previews in development mode
      !url.href.includes('/robots.txt') && // Skip robots for web crawlers
      !url.href.includes('/shell_') && // Don't fetch for shell.
      !url.href.includes('/sidekiq') && // Skip for Sidekiq dashboard
      !url.href.includes('/ahoy/') && // Skip for ahoy message redirects
      !url.href.includes('/abtests') && // Skip for field_test dashboard
      !url.href.includes('/social_previews') && // Skip for social previews
      !url.href.includes('/users/auth') && // Don't run on authentication.
      !url.href.includes('/enter') && // Don't run on registration.
      !url.href.includes('/sitemap-') && // Don't run on registration.
      !url.href.includes('/welcome') && // Don't run on welcome reroutes.
      !url.href.includes('/checkin') && // Don't run on checkin reroutes.

      // Don't run on search endpoints
      !url.href.includes('/search/tags') &&
      !url.href.includes('/search/chat_channels') &&
      !url.href.includes('/search/listings') &&
      !url.href.includes('/search/reactions') &&
      !url.href.includes('/search/feed_content') &&
      !url.href.includes('/search/users') &&

      // Don't run on harcoded redirects (see config/routes.rb for the list)
      !url.href.includes('/%F0%9F%92%B8') && // 💸 (hiring)
      !url.href.includes('/api') &&
      !url.href.includes('/future') &&
      !url.href.includes('/p/rlyweb') &&
      !url.href.includes('/podcasts') &&
      !url.href.includes('/shop') &&
      !url.href.includes('/survey') &&
      !url.href.includes('/workshops') &&

      caches.match('/shell_top') && // Ensure shell_top is in the cache.
      caches.match('/shell_bottom')) { // Ensure shell_bottom is in the cache.
      event.respondWith(createPageStream(event.request)); // Respond with the stream

      // Ping version endpoint to see if we should fetch new shell.
      if (!caches.match('/async_info/shell_version')) { // Check if we have a cached shell version
        caches.open(staticCacheName)
          .then(cache => cache.addAll([
            "/async_info/shell_version",
          ]));
        return;
      }

      fetch('/async_info/shell_version').then(response => response.json()).then(json => {
        caches.match('/async_info/shell_version').then(cachedResponse => cachedResponse.json()).then(cacheJson => {
          if (cacheJson['version'] != json['version']) {
            caches.open(staticCacheName)
              .then(cache => cache.addAll([
                "/shell_top",
                "/shell_bottom",
                "/async_info/shell_version"
              ]));
          }
        })
      })
      return;
    }

    // Fetch new shell upon events that signify change in session.
    if (event.clientId === "" &&
      (event.request.referrer.includes('/signout_confirm') || url.href.includes('?signin') || url.href.includes('/onboarding'))) {
      caches.open(staticCacheName)
        .then(cache => cache.addAll([
          "/shell_top",
          "/shell_bottom",
        ]));
    }
  }
});
