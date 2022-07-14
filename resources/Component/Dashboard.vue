<template>
    <div v-cloak class="dashboard">
        <div class="hello_user">
            <h1>
                privet user {{user.name}}
            </h1>
        </div>

        <div class="content">
            <div class="users">
                <p v-for="(friend,index) in friends" @click="openChat(index)"   :class="{active:friend.is_active!='0'}">{{friend.name}}</p>
            </div>
            <chat-component @closeChat="closeChat" :usersData="usersWithOpenChat[index]" :usersIds="chat"  v-for="(chat,index) in chats"></chat-component>
        </div>

    </div>

</template>

<script>
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    import ChatComponent from "./ChatComponent";
    export default {
        name: "Dashboard",
        components: {ChatComponent},
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
          }
        },
        methods:{
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
            let chatChannel=Echo.private('chat-channel.'+this.user.id);
            this.chatChannel=chatChannel;
            let userStateChannel=Echo.private('activeUsers');
            this.userStateChannel=userStateChannel;

            chatChannel.listen('SendMessageEvent',(chatMessage)=>{
                console.log(chatMessage)
                    this.showMessageInChat(chatMessage.from,chatMessage.message);

            })
             userStateChannel.listen('UserIsInactiveEvent',(inactiveUser)=>{
                 console.log(inactiveUser)
                 let userIndex=this.getUsersIndexById(inactiveUser.inactive_user);
                 this.setUserInctiveState(userIndex);
             }).listen('UserIsActiveEvent',(activeUser)=>{
                 console.log(activeUser)
                 let userIndex=this.getUsersIndexById(activeUser.active_user);
                 this.setUserActiveState(userIndex);
             })

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
