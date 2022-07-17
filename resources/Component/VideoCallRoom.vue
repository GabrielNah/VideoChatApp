<template>
    <div>
        <h1>{{callPartner.name}}</h1>
        <div class="video-container" >
            <div class="video-player" ></div>
        </div>
        <button @click="$emit('callEnded',callPartner.id)">End Call</button>
    </div>
</template>

<script>
    export default {
        name: "VideoCallRoom",
        props:['user','callPartner'],
        data(){
            return {
                localTracks:[],
                remoteUsers:[],
                app_id:"b5c8c3df2621406a80e2f2d616971085",
                token:"b5c8c3df2621406a80e2f2d616971085",
                channel:"MyAppChannel",
                UID:null,
                client:null,
            }
        },



    }
</script>

<style scoped>

</style>
