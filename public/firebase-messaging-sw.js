importScripts("https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyD68q1qNP-udtOOndJNd6ipHEsqO-iZEyM",
    authDomain: "laravel-web-dev.firebaseapp.com",
    projectId: "laravel-web-dev",
    storageBucket: "laravel-web-dev.firebasestorage.app",
    messagingSenderId: "537811764561",
    appId: "1:537811764561:web:429e5642c941aac61a7f70",
    measurementId: "G-PXD4TLQS7G"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Pesan background diterima:', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/logo.png'
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
