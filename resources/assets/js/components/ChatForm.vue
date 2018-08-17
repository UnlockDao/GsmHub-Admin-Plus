<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Nhập tin nhắn..." v-model="newMessage" @keyup.enter="sendMessage">
        <span class="input-group-btn">
            <a @click="sendMessage">
                <i class="material-icons">send</i>
            </a>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                newMessage: ''
            }
        },
        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                });
                this.newMessage = ''
            }
        }
    }
</script>