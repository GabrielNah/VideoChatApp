<template>

    <div class="mx-auto w-75 d-flex justify-content-center  flex-column">
        <div class="d-flex justify-content-center  flex-column align-items-center">
            <h3 class="w-50 text-xl-center">Sign In here </h3>
            <form action="" id="register" class="form-control w-50 d-flex flex-column justify-content-center ">
                <input name="name" v-model="name" placeholder="name"><br>
                <input name="email" v-model="email" placeholder="email"><br>
                <input name="password" v-model="password" type="password" placeholder="password"><br>
                <input type="password" v-model="password_confirmation" name="password_confirmation" placeholder="confirm your password"><br>
                <button  @click.prevent="sendRegisterRequest" class="btn btn-primary fw-bold"> Register</button>
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

</style>
