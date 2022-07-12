<template>
    <div>
        <div v-if="$store.state.UserLogedIn"><button class="leave"  @click="$store.dispatch('logout')">Log Out</button></div>
        <div  v-if="!$store.state.UserLogedIn" class="routes"><div><router-link   to="/login">Login</router-link><b>|</b><router-link  to="/register">Register</router-link></div></div>
    </div>
</template>

<script>
    export default {
        name: "NavbarComponent"
    }
</script>

<style scoped>
    .leave{
        margin-right:15px ;
        margin-top: 5px;
        float: right;
        clear: both;
    }
    .routes div{
        width: 30%;
    }
    .routes{
        margin-top: 5px;
        width: 30px;
        height: 10px;
        cursor: pointer;
        margin-left: 20px;
    }
</style>
