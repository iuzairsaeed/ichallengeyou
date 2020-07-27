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
// ----------------------------------------------------------

const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function (){
                    console.log("Notification permission granted.");
                    return messaging.getToken();
            }).then(function (token) {
                // $('#device_token').val(token);
                console.log(token)
            }).
            catch(function (err) {
                console.log('Unable to get permission to notify.', err);
            });

// ------------------------------------------------------------

messaging.onMessage( (payload) => {
    console.log(payload);
});

// ------------------------------------------------------------ 
firebase.analytics();

