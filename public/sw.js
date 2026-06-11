// Push Notification
self.addEventListener('push', function (e) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    if (e.data) {
        var msg = e.data.json();
        e.waitUntil(self.registration.showNotification(msg.title, {
            body: msg.body,
            icon: msg.icon || '/assets/Logo.png',
            badge: msg.badge || 'assets/Logo.png',
            vibrate: [200, 100, 200],
            data: msg.url || '/notifikasi'
        }));
    }
});


self.addEventListener('notificationclick', function (e) {
    e.notification.close();
    if (e.notification.data) {
        e.waitUntil(clients.openWindow(e.notification.data));
    }
});