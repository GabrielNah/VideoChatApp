<template>
    <div class="container-lg">
        <navbar-component class="mt-5"></navbar-component>
        <router-view></router-view>


    </div>
</template>

<script>
    import NavbarComponent from "./NavbarComponent";
    import router from "../js/router";
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    export default {
        name: "MainComponent",
        components: {NavbarComponent},
        async beforeMount() {
            let token=localStorage.getItem('userToken');

            if (token === null){
                this.$store.commit('changeUserStatus',false)
            }else {
                let UserLogedIn=await sendRequestWithBerarer.get('/checkToken')
                if(UserLogedIn){
                    this.$store.commit('changeUserStatus',true)
                }else {
                    localStorage.removeItem('userToken');
                    this.$store.commit('changeUserStatus',false)
                }

            }
        },
    }
</script>

<style scoped>

</style>
