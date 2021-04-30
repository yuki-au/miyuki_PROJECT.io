
// const version = 'v1';

// // cash when it installs
// self.addEventListener('install', (event) => {
//   console.log('service worker install ...');

//   // instal process wait untill cash finishes
//   event.waitUntil(
//     caches.open('v1').then((cache) => {
//       return cache.addAll([
//         './index.html',
//         './js/function.js',
//         './js/app.js',
//         './image/formlogo.png',
//         './css/style.css',
//         './css/bootstrap.min.css',
//         'https://use.fontawesome.com/releases/v5.6.1/css/all.css'
         
//       ]);
//     })
//   );
// });


// self.addEventListener('activate', (event) => {
//   console.info('activate', event);
// });

// self.addEventListener('fetch', function(event) {
//   console.log('fetch', event.request.url);

//   event.respondWith(

//     caches.match(event.request).then(function(cacheResponse) {

//       return cacheResponse || fetch(event.request).then(function(response) {
//         return caches.open('v1').then(function(cache) {

//           caches.put(event.request, response.clone());

//           return response;
//         });  
//       });
//     })
//   );
// });