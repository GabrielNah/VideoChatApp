<template>
<div class="page">
    <div class="forms">
    <div class="login">
        <div>
            <h3>Sign Up here here</h3>

            <form action="" id="login">
                <input name="email" placeholder="email"><br>
                <input name="password" type="password" placeholder="password"><br>
                <button @click.prevent="sendLoginRequest"> Login</button>
            </form>
        </div>
     </div>
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
    page{
        width: 30%;
        margin-left: 30%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    input{
        border: 1px solid black;
        background-color:#a0aec0;
    }
    .login{
        display: flex;
        justify-content: center;
        align-content: center;
    }
</style>
