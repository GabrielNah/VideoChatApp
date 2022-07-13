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
            <chat-component @closeChat="closeChat" :usersData="usersWithOpenChat[index]" :usersIds="chat" :newMessage="(receivedNewMessage && index==rightChatIndex ) ? newMessage : null" v-for="(chat,index) in chats"></chat-component>
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
              usersWithOpenChat:[]
          }
        },
        methods:{
            closeChat(chatsArray){
                console.log(chatsArray)
                let indexOfChatsArray=this.chats.indexOf(chatsArray)
                this.chats.splice(indexOfChatsArray,1);
                let indexOfFriend=this.usersWithOpenChat.indexOf(chatsArray[0]);
                this.usersWithOpenChat.splice(indexOfFriend,1)

            },
            checkIfItsRirghtChat(fromUser,message){
                let currentUser=this.user.id;
                let idsOfUsers=[];
                idsOfUsers.push(fromUser);
                idsOfUsers.push(currentUser);
                this.chats.forEach((chat,index)=>{
                    if(JSON.stringify(chat)==JSON.stringify(idsOfUsers)){
                        this.rightChatIndex=index;
                        this.newMessage=message;
                        this.receivedNewMessage=true;
                        console.log(this.rightChatIndex,this.newMessage,this.receivedNewMessage);
                    }
                })
            },
            showMessageInChat(friendId,message){
                let currentUserId=this.user.id;
                let idsOfUsers=[];
                idsOfUsers.push(friendId);
                idsOfUsers.push(currentUserId);
                let ifTheChatIsOpen=false;
                this.chats.forEach((chat)=>{
                    if(JSON.stringify(chat)==JSON.stringify(idsOfUsers)){
                        ifTheChatIsOpen=true;
                    }
                })
                if(!ifTheChatIsOpen){
                    this.chats.push(idsOfUsers);
                }
                if(ifTheChatIsOpen){
                    this.checkIfItsRirghtChat(friendId,message);
                }

            },
            openChat(someUsersIndex){
                let anotherUserId=this.friends[someUsersIndex].id
                let chat=[];
                chat.push(anotherUserId);
                chat.push(this.user.id)
                let ifChatIsOpen=this.chats.some((chatItem)=>{
                    return JSON.stringify(chat)==JSON.stringify(chatItem)
                });
                if(!ifChatIsOpen){
                    this.chats.push(chat)
                    this.usersWithOpenChat.push(this.friends[someUsersIndex])
                }

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
            Echo.private('chat-channel.'+this.user.id).
            listen('SendMessageEvent',(chatMessage)=>{
                console.log(chatMessage)
                    this.showMessageInChat(chatMessage.from,chatMessage.message);

            })
             Echo.private('activeUsers').listen('userIsActive',(e)=>{
                 console.log(e)
             }).listen('userIsInactive',(e)=>{
                 console.log(e)
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
