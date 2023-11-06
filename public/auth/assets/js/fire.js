import { initializeApp } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-app.js";
import { getMessaging } from 'https://www.gstatic.com/firebasejs/9.19.1/firebase-messaging.js';
import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-analytics.js";

// TODO: Add SDKs for Firebase products that you want to use

// https://firebase.google.com/docs/web/setup#available-libraries


// Your web app's Firebase configuration

// For Firebase JS SDK v7.20.0 and later, measurementId is optional

const firebaseConfig = {

  apiKey: "AIzaSyBM_u3KcQhs-AMKUsxPKRd-haTQvJGhWeM",

  authDomain: "test-KayTask.firebaseapp.com",

  projectId: "test-KayTask",

  storageBucket: "test-KayTask.appspot.com",

  messagingSenderId: "139283164553",

  appId: "1:139283164553:web:0dc123f07e377d14c34558",

  measurementId: "G-7X7KTGF38E"

};


// Initialize Firebase

const app = initializeApp(firebaseConfig);

const analytics = getAnalytics(app);
const messaging = getMessaging(app);

  // Request permission for push notifications
  Notification.requestPermission().then((permission) => {
    if(permission === "granted"){
        console.log('Permission granted');
    }

    // Get the user's FCM token
    messaging.getToken().then((token) => {
      console.log('FCM token:', token);
    });
  }).catch((error) => {
    console.log('Permission denied:', error);
  });
