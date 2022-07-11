<template>
    <div class="register">
        <div>
            <form action="" id="register">
            <input name="name" v-model="name" placeholder="name"><br>
            <input name="email" v-model="email" placeholder="email"><br>
            <input name="password" v-model="password" type="password" placeholder="password"><br>
            <input type="password" v-model="password_confirmation" name="password_confirmation" placeholder="confirm your password"><br>
                <button type="submit" @click.prevent="sendRegisterRequest"> Register</button>
            </form>
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
