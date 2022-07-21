<template>
    <div class="mx-auto w-75 d-flex justify-content-center  flex-column">
            <div class="d-flex justify-content-center  flex-column align-items-center">
                <h3 class="w-50 text-xl-center">Sign Up here here</h3>
                <form action="" id="login" class="form-control w-50 d-flex flex-column justify-content-center ">
                    <input name="email" placeholder="email"><br>
                    <input name="password" type="password" placeholder="password"><br>
                    <button @click.prevent="sendLoginRequest" class="btn-dark btn fw-bold"> Login</button>
                </form>
            </div>


    </div>
</template>

<script>
    export default {
        name: "LoginComponent",
        methods:{
            sendLoginRequest(){
                let credentials=new FormData(document.querySelector('#login'))
                this.$store.dispatch('login',{credentials});
            }
        }
    }
</script>

<style scoped>

</style>
