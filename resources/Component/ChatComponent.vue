<template>
    <div class="d-inline-flex flex-column ">
        <div class="d-flex flex-row w-100 justify-content-between" ><p class="fw-bold">{{friendsData.name}}</p><button class="btn btn-danger" @click="sendVideoCallRequest">Call</button><button class="btn-close" @click="$emit('closeChat',chatIndex)"></button></div>
        <div class="w-100 messages">

            <p v-for="message in messages" class="rounded fw-bold w-auto text-bg-secondary" :class="{ fromMe:message.from==currentUserId,toMe:message.from!=currentUserId}">{{message.message}}</p>

        </div>
        <p class="typing fw-bold text-bg-primary"  v-if="userIsTyping">{{friendsData.name}} is Typing...</p>
        <div class="sendMessage">
            <input ref="chatMessage" class="w-75" @keydown="sendTypingEvent" type="text"><button class="w-25 btn-primary" @click="sendMessage">Send</button>
        </div>
    </div>
</template>

<script>
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    export default {
        name: "ChatComponent",
        props:['usersIds','newChatMessage','usersData','chatIndex'],
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
    .messages{
        height: 250px;
        overflow-y: scroll;
        background-color: green;
    }
    .fromMe{
        float: right;
        clear: both;

    }
    .toMe{
        float: left;
        clear: both;
    }

</style>
