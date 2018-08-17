<template>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <i class="material-icons">notifications</i>
             <span class="notification">{{ notifications.length }}</span>
        </a>

        <ul class="dropdown-menu" role="menu" style="width:320px">
            <li v-for="notification in notifications">
                <a href="#" v-on:click="MarkAsRead(notification)">
            <li>

                <div class="row">
                    <div class="col-md-2">
                        <img src="assets/img/favicon.png"
                             style="width:40px; background:#fff; border:1px solid #eee" class="img-rounded">
                    </div>

                    <div class="col-md-10">

                        <b style="color:green; font-size:90%">{{ notification.data.user }} </b>
                        <span style="color:#000; font-size:90%">{{ notification.data.data }}</span>
                        <br/>
                        <small style="color:#90949C">{{ notification.created_at }} <i aria-hidden="true" class="fa fa-users"></i>
                        </small>
                    </div>

                </div>
            </li>
                </a>
            </li>
            <li v-if="notifications.length == 0">
                Không có thông báo nào
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        props: ['notifications'],
        methods: {
            MarkAsRead: function(notification) {
                var data = {
                    id: notification.id
                };
                axios.post('/notification/read', data).then(response => {
                    window.location.href = "/donhang/" + notification.data.id;
                });
            }
        }
    }
</script>