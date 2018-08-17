<template>
    <ul class="chat" v-chat-scroll>
        <li class="left clearfix" v-for="message in messages">
            <div class="chat-body clearfix">
                <div>
                    <p style="font-size:10px">
                        {{ message.user.name }}
                    </p>
                </div>
                <strong>
                    {{ message.message }}
                </strong>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['messages']
    };
</script>
