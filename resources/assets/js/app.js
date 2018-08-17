/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('chat-messages', require('./components/ChatMessages.vue'));
Vue.component('chat-form', require('./components/ChatForm.vue'));
const app = new Vue({
    el: '#noti',
    data: {
        notifications: '',
    },
    created() {
        axios.post('/notification/get').then(response => {
            this.notifications = response.data;
    });

        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.User.' + userId).notification((notification) => {
            this.notifications.push(notification);
        document.getElementById("noty_audio").play();
    });
    }
});
const mess = new Vue({
    el: '#mess',
    data: {
        messages: []
    },
    created() {
        this.fetchMessages();
        Echo.private('chat')
            .listen('MessageSent', (e) => {
            this.messages.push({
            message: e.message.message,
            user: e.user
        });
        document.getElementById("tinhan").play();
    });
    },

    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data;
        });
        },

        addMessage(message) {
            this.messages.push(message);

            axios.post('/messages', message).then(response => {
                console.log(response.data);
        });
        }
    }
});