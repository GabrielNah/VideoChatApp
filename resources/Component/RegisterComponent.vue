<template>
    <div class="page">
        <div class="forms">


    <div class="register">
        <div>
            <h3>Register here</h3>
            <form action="" id="register">
            <input name="name" v-model="name" placeholder="name"><br>
            <input name="email" v-model="email" placeholder="email"><br>
            <input name="password" v-model="password" type="password" placeholder="password"><br>
            <input type="password" v-model="password_confirmation" name="password_confirmation" placeholder="confirm your password"><br>
                <button type="submit" @click.prevent="sendRegisterRequest"> Register</button>
            </form>
        </div>

    </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "RegisterComponent",
        data(){
            return {
                    name:null,
                    email:null,
                    password:null,
                    password_confirmation:null
            }

        },
        methods:{
             sendRegisterRequest(){

                let editableCredentials=new FormData(document.querySelector('#register'))
                 let credentials=Object.fromEntries(editableCredentials.entries())
                this.$store.dispatch('registration',{credentials});
            },
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
        background-color: #a0aec0;
    }
.register{
    display: flex;
    justify-content: center;
    align-content: center;
}
</style>
