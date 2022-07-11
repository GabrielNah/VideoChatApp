<template>
    <div class="page">
        <div><button v-if="$store.state.UserLogedIn" @click="$store.dispatch('logout')">Log Out</button></div>
        <div class="routes"><router-link v-if="!$store.state.UserLogedIn" to="/login">Login</router-link>|<router-link v-if="!$store.state.UserLogedIn" to="/register">Register</router-link></div>
        <div class="forms">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MainComponent"
    }
</script>

<style scoped>
.page{
    width: 300px;
    margin-left: 30%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>
