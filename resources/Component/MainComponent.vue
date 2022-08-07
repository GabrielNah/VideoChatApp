<template>
    <div class="container-lg">
        <navbar-component class="mt-5"></navbar-component>
        <router-view></router-view>
        <template>
            <footer>
                <h1>footer</h1>
            </footer>
        </template>
        <div>
            esim
        </div>

    </div>
</template>

<script>
    import NavbarComponent from "./NavbarComponent";
    import router from "../js/router";
    import sendRequestWithBerarer from "../js/axios/sendRequestWithBearer";
    export default {
        name: "MainComponent",
        components: {NavbarComponent},
    }
</script>

<style scoped>

</style>
