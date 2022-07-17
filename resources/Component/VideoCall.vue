<template>
    <div>
        <h1>{{user.name}} is calling...</h1>
        <div><button class="ok" @click="acceptVideoCall">accept</button><button @click="rejectVideoCall" class="reject">reject</button></div>
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
