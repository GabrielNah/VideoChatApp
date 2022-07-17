<template>
    <div>
        <h1> You are calling to {{userData.name}}</h1>
        <button class="end" @click="$emit('cancelCall')">End the Call</button>
    </div>
</template>

<script>
    export default {
        name: "VideoCallRequest",
        props:['userData']
    }
</script>

<style scoped>
.end{
    background-color: red;
}
</style>
