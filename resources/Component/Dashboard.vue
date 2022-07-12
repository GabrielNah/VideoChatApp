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
            <chat-component :usersIds="chat" v-for="chat in chats"></chat-component>
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
              chats:[]
          }
        },
        methods:{
            openChat(someUsersIndex){
                let anotherUserId=this.friends[someUsersIndex].id
                let chat=[];
                chat.push(anotherUserId);
                chat.push(this.user.id)
                this.chats.push(chat)
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
        margin-right: 5px;
    }


</style>
