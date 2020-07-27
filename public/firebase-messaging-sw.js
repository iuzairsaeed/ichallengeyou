importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyBBMKPyLfIbfS6hHAVTHGpli-ai32u-Wso",
    authDomain: "i-challenge-you-ac517.firebaseapp.com",
    databaseURL: "https://i-challenge-you-ac517.firebaseio.com",
    projectId: "i-challenge-you-ac517",
    storageBucket: "i-challenge-you-ac517.appspot.com",
    messagingSenderId: "482874118159",
    appId: "1:482874118159:web:5c7df9d3b0fb422c57937a",
    measurementId: "G-6SL9C5SXKW"
};

firebase.initializeApp(firebaseConfig);

var messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log('[firebase-messagging-sw.js] Recieved background message ', payload );
    var notificationTitle = 'Background Message Title';
    var notificationOptions = {
        body: 'Background Message body',
        icon: '/firebase-logo.png'
    };

    return self.registration.showNotification(notificationTitle,notificationOptions);
});