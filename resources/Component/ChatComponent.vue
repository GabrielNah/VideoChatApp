<template>
    <div class="chat">
        <div class="username"><p>{{friendsData.name}}</p><button @click="$emit('closeChat',usersIds)">&#x2715</button></div>
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
        props:['usersIds','newMessage','usersData'],
        data(){
            return{
                currentUserId:null,
                anotherUserId:null,
                messages:[],
                newMessage:this.newMessage,
                friendsData:this.usersData
            }
        },
        watch:{
            newMessage: {
                handler(newMessage, childMessage) {
                    let messageData = {
                        from: this.anotherUserId,
                        to: this.currentUserId,
                        message: newMessage
                    }
                    this.messages.push(messageData)
                },
                deep:true
            },

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
                if(message != ""){
                    let data={
                        from:this.currentUserId,
                        to:this.anotherUserId,
                        message
                    }
                    this.$refs.chatMessage.value='';
                    await sendRequestWithBerarer.post('/message',data).then(sendedMessage=>{
                        this.messages.push(sendedMessage.data);
                    });
                }


            }
        },
        async created() {

            await this.getChatMessages()

        }
    }
</script>

<style scoped>
    .username{
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: space-between;
    }
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
        overflow-y: scroll;
        /*overflow: hidden;*/
        margin: 0px 0px 0px 0px ;
        height: 80%;
        width: 100%;
        background-color: #cbd5e0;
    }
    .messages p{
        font-weight: normal;
        background-color: aqua;
        width: fit-content;
        border-radius:10% ;
    }
    input{
        background-color: #a0aec0;
        width: 80%;
    }
</style>
