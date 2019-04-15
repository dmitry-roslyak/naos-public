// window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');

window.Vue = require('vue');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.getElementsByName('csrf-token')[0].content,
    'X-Requested-With': 'XMLHttpRequest'
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'redis'
// });
// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

// var pusher = new Pusher('69e878ea5991b6099fb6', {
//   cluster: 'eu',
//   encrypted: true
// });

// var channel = pusher.subscribe('my-channel');
// channel.bind('my-event', function(data) {
//   alert(data.message);
// });
window.Validator = require('./validate.js').default;

var webSocketPromise, developmentModeMsg = "WebSocket disabled in development mode";

if(__NODE_ENV === 'production'){

    webSocketPromise = new Promise((resolve, reject) => {
        const webSocket = new WebSocket("wss://ws-eu.pusher.com:443/app/69e878ea5991b6099fb6?protocol=7&client=js&version=4.1.0&flash=false");
        webSocket.onopen = () => resolve(webSocket);
        webSocket.onerror = (event) => reject(event);
    });

} else {
    webSocketPromise = new Promise((resolve, reject) => reject(developmentModeMsg))
}

webSocketPromise.catch(function (e) {
    if(developmentModeMsg == e) {
        console.warn(developmentModeMsg)
        return;
    }
    console.error(e)
});
window.webSocketPromise = webSocketPromise;

window.debounce = function (func, timeout) {
    var id;
    return function () {
        f = func.bind(this, ...arguments)
        
        if(id) clearTimeout(id);

        id = setTimeout(f, timeout);
    }
}

window.throttle = function (func, timeout) {
    var id;
    return function () {
        if(!id){ 
            func.apply(this, arguments);
            id = setTimeout(function () {
                id = null;
            }, timeout);
        }
    }
}