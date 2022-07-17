<template>
    <div v-cloak class="dashboard">
        <div class="hello_user">
            <h1>
                privet user {{user.name}}
            </h1>
        </div>
        <div>
            <video-call-room @callEnded="endCall" v-if="onGoingCall" :user="user" :callPartner="onGoingCall == 'inComing' ? callerData:callReceiver"> </video-call-room>
            <video-call @callRejected="rejectVideoCallRequest" @callAccepted="acceptVideoCallRequest" v-if="incomingCall" :user="callerData"></video-call>
            <video-call-request  @cancelCall="cancelCallRequest" v-if="outGoingCall" :userData="callReceiver"></video-call-request>
        </div>

        <div class="content">
            <div class="users">
                <p v-for="(friend,index) in friends" @click="openChat(index)"   :class="{active:friend.is_active!='0'}">{{friend.name}}</p>
            </div>
            <chat-component @sendCall="sendCallRequest" @calling="receiveCalls" @closeChat="closeChat" :usersData="usersWithOpenChat[index]" :usersIds="chat"  v-for="(chat,index) in chats"></chat-component>
        </div>

    </div>

</template>

<script>
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    import ChatComponent from "./ChatComponent";
    import VideoCall from "./VideoCall";
    import VideoCallRequest from "./VideoCallRequest";
    import VideoCallRoom from "./VideoCallRoom";
    export default {
        name: "Dashboard",
        components: {VideoCallRoom, VideoCallRequest, VideoCall, ChatComponent},
        data(){
          return{
              user:null,
              friends:null,
              chats:[],
              receivedNewMessage:false,
              newMessage:null,
              rightChatIndex:null,
              usersWithOpenChat:[],
              chatChannel:null,
              userStateChannel:null,
              callerData:null,
              incomingCall:false,
              callAndTypingChannel:null,
              callReceiver:null,
              outGoingCall:false,
              onGoingCall:false,

          }
        },
        methods:{
            endCall(callPartnerId){
                this.userStateChannel.whisper('callEnded',{
                    callEndedBy:this.user.id,
                    callPartnerId,
                })
                if(this.onGoingCall=="inComing"){
                    this.onGoingCall=false;
                    this.callerData=null;
                }
                if(this.onGoingCall="outGoing"){
                    this.onGoingCall=false;
                    this.callReceiver=null;
                }
            },
            handleEndedCalls(){
                this.userStateChannel.listenForWhisper('callEnded',(endedCallData)=>{
                    if(endedCallData.callPartnerId==this.user.id){
                        if(this.onGoingCall=="inComing"){
                            this.callerData=null;
                        }
                        if (this.onGoingCall="outGoing"){
                            this.callReceiver=null;
                        }
                        this.onGoingCall=false;
                    }

                })
            },
            cancelCallRequest(){
                    this.userStateChannel.whisper('callCanceled',{
                        callTo:this.callReceiver.id,
                        callFrom:this.user.id
                    })
                this.outGoingCall=false;
                this.callReceiver=null;

            },
            handleCanceledCalls(){
                this.userStateChannel.listenForWhisper('callCanceled',(callData)=>{
                    if(callData.callTo==this.user.id){
                        if(callData.callFrom==this.callerData.id){
                            this.incomingCall=false;
                            this.callerData=null;
                        }
                    }
                })

            },
            acceptVideoCallRequest(callFrom) {
                this.incomingCall=false
                this.onGoingCall="inComing";

                this.userStateChannel.whisper('callAccepted',{
                    callFrom,
                    acceptedFrom:this.user.id
                });


            },
            handleAcceptedVideoCallRequest(){
                this.userStateChannel.listenForWhisper('callAccepted',(acceptedCallData)=>{
                    if(this.user.id==acceptedCallData.callFrom){
                        if(this.callReceiver.id==acceptedCallData.acceptedFrom){
                            this.onGoingCall="outGoing";
                            this.outGoingCall=false;
                        }
                    }
                })
            },
            handleRejectedVideoCallRequest(){
                this.userStateChannel.listenForWhisper('callRejected',(rejectedCallData)=>{
                    if(rejectedCallData.rejectedCallOf==this.user.id){
                        if(rejectedCallData.rejectedFrom==this.callReceiver.id){
                            this.outGoingCall=false;
                            this.callReceiver=null;
                        }
                    }
                })
            },
            rejectVideoCallRequest(rejectedCallOf) {
                this.userStateChannel.whisper('callRejected',{
                    rejectedFrom:this.user.id,
                    rejectedCallOf,
                });
                this.incomingCall=false;
                this.callerData=null;

            },
            sendCallRequest(userId){
                let user=this.friends[this.getUsersIndexById(userId)]
                this.callReceiver=user;
                this.outGoingCall=true;
            },
            receiveCalls(videoChatRequest){
                if(videoChatRequest.callingTo==this.user.id){
                    let caller=this.getUsersIndexById(videoChatRequest.caller);
                    this.callerData=this.friends[caller];
                    this.incomingCall=true;

                }
            },
            closeChat(chatsArray){
                console.log(chatsArray)
                let indexOfChatsArray=this.getChatsIndex(chatsArray);
                this.chats.splice(indexOfChatsArray,1);
                let indexOfFriend=this.usersWithOpenChat.indexOf(chatsArray[0]);
                this.usersWithOpenChat.splice(indexOfFriend,1)

            },
            getChatsIndex(chat){
                let chatIndex;
                this.chats.forEach((chatItem,indexOfChat)=>{
                    if(JSON.stringify(chatItem)==JSON.stringify(chat)){
                        chatIndex=indexOfChat;
                    }
                })
                return chatIndex;
            },
            checkIfChatIsOpen(chat){
               return  this.chats.some((chatItem,index)=>JSON.stringify(chatItem) == JSON.stringify(chat))
            },
            setUserActiveState(userIndex){
                this.friends[userIndex].is_active=true
            },
            setUserInctiveState(userIndex){
                this.friends[userIndex].is_active=false
            },
            getUsersIndexById(id){
                let userIndex;
                this.friends.forEach((friend,indexOfFriend)=>{
                    if(friend.id==id){
                        userIndex=indexOfFriend
                    }
                })
                return userIndex;
            },
            showMessageInChat(friendId,message){
                let currentUserId=this.user.id;
                let idsOfUsers=[];
                idsOfUsers.push(friendId);
                idsOfUsers.push(currentUserId);

                if(!this.checkIfChatIsOpen(idsOfUsers)){
                    this.chats.push(idsOfUsers);
                    let friendUser=this.friends.filter(usersData=>usersData.id=friendId);
                    this.usersWithOpenChat.push(friendUser[0])
                }

            },
            openChat(someUsersIndex){
                let anotherUserId=this.friends[someUsersIndex].id
                let chat=[];
                chat.push(anotherUserId);
                chat.push(this.user.id);
                if(!this.checkIfChatIsOpen(chat)){
                    this.chats.push(chat)
                    this.usersWithOpenChat.push(this.friends[someUsersIndex])
                }

            },
            catchVideoCalls(){
                this.userStateChannel.listenForWhisper('calling',(callMessage)=>{
                    console.log(callMessage)
                    this.receiveCalls(callMessage)

                })
            },
            handleIncomingMessages(){
                this.chatChannel.listen('SendMessageEvent',(chatMessage)=>{
                    console.log(chatMessage)
                    this.showMessageInChat(chatMessage.from,chatMessage.message);

                })
            },
            handleUsersState(){
                this.userStateChannel.listen('UserIsInactiveEvent',(inactiveUser)=>{
                    console.log(inactiveUser)
                    let userIndex=this.getUsersIndexById(inactiveUser.inactive_user);
                    this.setUserInctiveState(userIndex);
                }).listen('UserIsActiveEvent',(activeUser)=>{
                    console.log(activeUser)
                    let userIndex=this.getUsersIndexById(activeUser.active_user);
                    this.setUserActiveState(userIndex);
                })
            }
        },
        async beforeCreate(){
               await sendRequestWithBerarer.get('/user',).then((usersData)=>{
                         console.log(usersData)
                   this.user=usersData.data.user
               })
                 await sendRequestWithBerarer.get('/users',).then((allUsers)=>{
                         console.log(allUsers)
                         this.friends=allUsers.data

                 })

             },
         mounted() {
             this.chatChannel=Echo.private('chat-channel.'+this.user.id);
             this.userStateChannel=Echo.private('activeUsers');
            this.handleIncomingMessages();
            this.handleUsersState();
             this.catchVideoCalls();
            this.handleCanceledCalls();
            this.handleRejectedVideoCallRequest();
            this.handleAcceptedVideoCallRequest()
             this.handleEndedCalls();

        }


    }
</script>

<style scoped>
    .hello_user{
        height: 20px;
    }
    .content{
        height: 80%;
        position: relative;
    }
    .active{
        background-color: green;
    }

    .dashboard{
        width: 100%;
        position: relative;
    }
.users {
    position: absolute;
    right: 0;
    top: 0;
}
    .users p{
        font-weight: bolder;
        margin-right: 5px;
    }


</style>
