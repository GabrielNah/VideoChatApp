<template>
    <div class="chat">
        <div class="messages">
            <p v-for="message in messages" :class="{ fromMe:message.from==currentUserId,toMe:message.from!=currentUserId}">{{message.message}}</p>
        </div>
        <div class="sendMessage">
            <input ref="chatMessage" type="text"><button @click="sendMessage">Send</button>
        </div>
    </div>
</template>

<script>
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    export default {
        name: "ChatComponent",
        props:['usersIds'],
        data(){
            return{
                currentUserId:null,
                anotherUserId:null,
                messages:[]
            }
        },
        methods:{
            async getChatMessages(ChatIndex){
                this.currentUserId=this.usersIds[1];
                this.anotherUserId=this.usersIds[0];
                await sendRequestWithBerarer.post('/chat',{to:this.anotherUserId,from:this.currentUserId})
                    .then(chatMessages=> {
                       this.messages=chatMessages.data
                    })
            },
            async sendMessage(){
                let message=this.$refs.chatMessage.value
                let data={
                    from:this.currentUserId,
                    to:this.anotherUserId,
                    message
                }
               await sendRequestWithBerarer.post('/message',data).then(x=>console.log(x));
            }
        },
        async created() {
            await this.getChatMessages()
            Echo.private('chat-channel.'+this.currentUserId).listen('realTimeNotifications',(e)=>{console.log(e)})
        }
    }
</script>

<style scoped>
    .fromMe{
        float: right;
        clear: both;
    }
    .toMe{
        float: left;
        clear: both;
    }
    .sendMessage{
        width: 100%;
        height: 25%;
    }
    .sendMessage button{
        width: 20%;
        margin: 0px 0px 0px 0px ;
    }
    .chat{
        margin-top: 25%;
        display: inline-flex;
        flex-direction: column;
        border: 2px solid black;
        width: 20%;
        height: 60%;
    }
    .messages{
        margin: 0px 0px 0px 0px ;
        height: 80%;
        width: 100%;
        background-color: #cbd5e0;
    }
    .messages p{
        background-color: aqua;
        width: fit-content;
        border-radius:10% ;
    }
    input{
        background-color: #a0aec0;
        width: 80%;
    }
</style>
