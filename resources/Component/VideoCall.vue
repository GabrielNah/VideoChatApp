<template>
    <div class="mt-3 ">
        <h1 class="text-bg-primary rounded text-xl-center">{{user.name}} is calling...</h1>
        <div class="d-flex justify-content-center align-items-center"><button class="btn ok fw-bold" @click="acceptVideoCall">accept</button><button @click="rejectVideoCall" class=" btn reject fw-bold">reject</button></div>
    </div>
</template>

<script>
    export default {
        name: "VideoCall",
        props:['user'],
        methods:{
            acceptVideoCall(){
                this.$emit('callAccepted',this.user.id)
            },
            rejectVideoCall(){
                this.$emit('callRejected',this.user.id)
            },

        }
    }
</script>

<style scoped>
.ok{
    background-color: green;
}
    .reject{
        background-color: red;
    }
</style>
