<template>
    <div class="mt-3 ">
        <h1 class="text-bg-primary rounded text-xl-center"> You are calling to {{userData.name}} ...</h1>
        <div class="d-flex justify-content-center align-items-center">
            <button class="btn btn-danger fw-bold " @click="$emit('cancelCall')">End the Call</button>
        </div>
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
