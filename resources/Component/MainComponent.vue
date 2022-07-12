<template>
    <div>
        <navbar-component class="navbar"></navbar-component>
        <router-view></router-view>


    </div>
</template>

<script>
    import NavbarComponent from "./NavbarComponent";
    import router from "../js/router";
    export default {
        name: "MainComponent",
        components: {NavbarComponent},
        async beforeMount() {
            let token=localStorage.getItem('userToken');
            if (token == null){
                this.$store.commit('changeUserStatus',false)
            }else {
                this.$store.commit('changeUserStatus',true)
            }
        }
    }
</script>

<style scoped>
    .navbar{
        width: 100%;
        height: 50px;
        margin: 10px 0 10px 0;
    }
</style>
