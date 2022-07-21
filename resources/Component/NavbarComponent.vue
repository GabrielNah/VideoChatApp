<template>
    <div class="d-flex justify-content-center pb-3">
        <div v-if="$store.state.UserLogedIn" class="nav nav-tabs w-100 d-flex justify-content-between">
            <router-link  v-if="$store.state.UserLogedIn" class="link-primary fw-bold fs-5" to="/Dashboard">Dashboard</router-link>
            <button   @click="$store.dispatch('logout')" class="link-danger fw-bold">Log Out</button>
        </div>
        <div  v-if="!$store.state.UserLogedIn" class="nav nav-tabs w-25 d-flex justify-content-evenly">
            <router-link class="link-primary fw-bold fs-4"  to="/login">Login</router-link>
            <router-link  class="link-primary fw-bold fs-4" to="/register">Register</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NavbarComponent"
    }
</script>

<style scoped>

</style>
