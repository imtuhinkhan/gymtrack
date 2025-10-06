// Service Worker for Gym Management System PWA
const CACHE_NAME = 'gym-management-v3';
const urlsToCache = [
    '/',
    '/manifest.json',
    // Only include assets that definitely exist
];

// Install event - cache resources
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                // Use addAll with error handling
                return Promise.allSettled(
                    urlsToCache.map(url => 
                        cache.add(url).catch(error => {
                            console.warn(`Failed to cache ${url}:`, error);
                            return null; // Don't fail the entire installation
                        })
                    )
                );
            })
            .then(results => {
                const successful = results.filter(r => r.status === 'fulfilled').length;
                const failed = results.filter(r => r.status === 'rejected').length;
                console.log(`Cache installation: ${successful} successful, ${failed} failed`);
            })
            .catch(error => {
                console.error('Cache installation failed:', error);
            })
    );
});

// Fetch event - serve cached content when offline
// self.addEventListener('fetch', event => {
//     // Skip non-GET requests and unsupported schemes
//     if (event.request.method !== 'GET') {
//         return;
//     }
    
//     // Skip chrome-extension, chrome, and other unsupported schemes
//     const url = new URL(event.request.url);
//     if (url.protocol === 'chrome-extension:' || 
//         url.protocol === 'chrome:' || 
//         url.protocol === 'moz-extension:' ||
//         url.protocol === 'safari-extension:' ||
//         url.protocol === 'ms-browser-extension:') {
//         return;
//     }
    
//     // Skip requests to external domains (only cache same-origin requests)
//     if (url.origin !== location.origin) {
//         return;
//     }
    
//     event.respondWith(
//         caches.match(event.request)
//             .then(response => {
//                 // Return cached version or fetch from network
//                 if (response) {
//                     return response;
//                 }
                
//                 // Clone the request because it's a stream
//                 const fetchRequest = event.request.clone();
                
//                 return fetch(fetchRequest).then(response => {
//                     // Check if we received a valid response
//                     if (!response || response.status !== 200 || response.type !== 'basic') {
//                         return response;
//                     }
                    
//                     // Only cache same-origin requests
//                     if (response.url.startsWith(location.origin)) {
//                         // Clone the response because it's a stream
//                         const responseToCache = response.clone();
                        
//                         caches.open(CACHE_NAME)
//                             .then(cache => {
//                                 cache.put(event.request, responseToCache);
//                             })
//                             .catch(error => {
//                                 console.warn('Failed to cache response:', error);
//                             });
//                     }
                    
//                     return response;
//                 }).catch(() => {
//                     // If both cache and network fail, show offline page
//                     if (event.request.destination === 'document') {
//                         return caches.match('/offline');
//                     }
//                 });
//             })
//     );
// });

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Background sync for offline data
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

// Push notification handling
self.addEventListener('push', event => {
    const options = {
        body: event.data ? event.data.text() : 'New notification from Gym Management',
        icon: '/icon-192x192.png',
        badge: '/icon-192x192.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'View Details',
                icon: '/icon-192x192.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/icon-192x192.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('Gym Management', options)
    );
});

// Notification click handling
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    } else if (event.action === 'close') {
        // Just close the notification
    } else {
        // Default action - open the app
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Helper function for background sync
async function doBackgroundSync() {
    try {
        // Implement your background sync logic here
        // For example, sync offline form submissions, etc.
        console.log('Background sync completed');
    } catch (error) {
        console.error('Background sync failed:', error);
    }
}

// Message handling for communication with main thread
self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});