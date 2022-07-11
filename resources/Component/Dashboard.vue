<template>
    <div v-cloak>
     <h1>
        privet user {{user.name}}
    </h1>

    </div>

</template>

<script>
    export default {
        name: "Dashboard",
        data(){
          return{
              user:null
          }
        },
        methods:{
        },
        async beforeCreate(){
            let userToken=localStorage.getItem('userToken');
             if(userToken){
               let userData=await axios.get('/api/v1/user',{headers:{
                         'Authorization': 'Bearer '+userToken
                     }}).then((usersData)=>{
                         console.log(usersData)
                   this.user=usersData.data.user
               })

             }
        },
    }
</script>

<style scoped>

</style>
