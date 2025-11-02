// Import Firebase script
importScripts("https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js");

// Konfigurasi Firebase
firebase.initializeApp({
  apiKey: "AIzaSyD68q1qNP-udtOOndJNd6ipHEsqO-iZEyM",
    authDomain: "laravel-web-dev.firebaseapp.com",
    projectId: "laravel-web-dev",
    storageBucket: "laravel-web-dev.firebasestorage.app",
    messagingSenderId: "537811764561",
    appId: "1:537811764561:web:429e5642c941aac61a7f70",
    measurementId: "G-PXD4TLQS7G"
});

// Inisialisasi messaging
const messaging = firebase.messaging();

// Tangani pesan yang diterima saat browser di background
messaging.onBackgroundMessage(function (payload) {
  console.log('[firebase-messaging-sw.js] Pesan background diterima:', payload);

  const notificationTitle = payload.notification?.title || 'Notifikasi Baru';
  const notificationOptions = {
    body: payload.notification?.body || 'Anda memiliki pesan baru.',
    icon: payload.notification?.icon || '/icon.png',
    data: payload.data || {},
  };

  // Tampilkan notifikasi
  self.registration.showNotification(notificationTitle, notificationOptions);
});

// (Opsional) Tangani klik pada notifikasi
self.addEventListener('notificationclick', function (event) {
  event.notification.close();
  const urlToOpen = event.notification?.data?.link || 'https://infruity.com';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      for (const client of clientList) {
        if (client.url === urlToOpen && 'focus' in client) return client.focus();
      }
      if (clients.openWindow) return clients.openWindow(urlToOpen);
    })
  );
});