<template>
    <div class="chat">
        <div class="username"><p>{{friendsData.name}}</p><button @click="sendVideoCallRequest">Call</button><button @click="$emit('closeChat',usersIds)">&#x2715</button></div>
        <div class="messages">

            <p v-for="message in messages" :class="{ fromMe:message.from==currentUserId,toMe:message.from!=currentUserId}">{{message.message}}</p>

        </div>
        <p class="typing" v-if="userIsTyping"><b>{{friendsData.name}} is Typing</b></p>
        <div class="sendMessage">
            <input ref="chatMessage" @keydown="sendTypingEvent" type="text"><button @click="sendMessage">Send</button>
        </div>
    </div>
</template>

<script>
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    export default {
        name: "ChatComponent",
        props:['usersIds','newChatMessage','usersData'],
        data(){
            return{
                currentUserId:this.usersIds[1],
                anotherUserId:this.usersIds[0],
                messages:[],
                newMessage:this.newChatMessage,
                friendsData:this.usersData,
                activeUsersChannel:null,
                userIsTyping:false,
            }
        },
        methods:{
            sendVideoCallRequest(){

                setTimeout( () => {
                    this.activeUsersChannel.whisper('calling', {
                        caller: this.currentUserId,
                        callingTo:this.anotherUserId
                    })
                }, 300);
                this.$emit('sendCall',this.anotherUserId);
            },
            async getChatMessages(ChatIndex){
                await sendRequestWithBerarer.post('/chat',{to:this.anotherUserId,from:this.currentUserId})
                    .then(chatMessages=> {
                       this.messages=chatMessages.data;

                    })
            },
            sendTypingEvent(){
                setTimeout( () => {
                    this.activeUsersChannel.whisper('typing', {
                        user: this.currentUserId,
                        typing: true
                    })
                }, 300)
            },
            showUserIsTypingMessage(typingMessage){
                console.log(typingMessage)
                if(this.anotherUserId == typingMessage.user){
                    this.userIsTyping=true;
                    setTimeout( ()=>{
                        this.userIsTyping=false;
                    }, 1000)
                }
            },
            catchTypingEvent(){
                this.activeUsersChannel.listenForWhisper('typing',(typingMessage)=>{
                    this.showUserIsTypingMessage(typingMessage)
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


            },
            changeScrollPosition(){
                document.querySelector('.messages').scrollBy(0,document.querySelector('.messages').scrollHeight)

            },
            listenForLiveMessages(){
                Echo.private('chat-channel.'+this.currentUserId).
                listen('SendMessageEvent',(chatMessage)=>{
                    console.log(chatMessage)
                    chatMessage.to=this.currentUserId;
                    this.messages.push(chatMessage);

                })
            }
        },
        async created() {

            await this.getChatMessages();
            await this.changeScrollPosition();
        },
        mounted() {
            this.activeUsersChannel=Echo.private('activeUsers');
            this.listenForLiveMessages();
            this.catchTypingEvent();

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
        justify-content: space-evenly;
        border: 2px solid black;
        width: 20%;
        height: 60%;
        position: relative;
    }
    .messages{
        overflow-y: scroll;
        overflow-x: hidden;
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
    .typing{
        background-color:chartreuse;
        width: 100%;
        height: 15px;
        font-size: 15px;
    }
    input{
        background-color: #a0aec0;
        width: 80%;
    }
</style>
