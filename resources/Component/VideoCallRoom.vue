<template>
    <div v-cloak>
        <h1>{{callPartner.name}}</h1>
        <div class="video-container" :id="'user-container'+UID">
            <div class="video-player" :id="'user-'+UID"></div>
        </div>
        <div class="video-container" :id="'user-container'+remoteUser.uid" v-for="remoteUser in remoteUsers">
            <div class="video-player" :id="'user-'+remoteUser.uid"></div>
        </div>
        <button @click="endCall">End Call</button>
    </div>
</template>

<script>
    export default {
        name: "VideoCallRoom",
        props:['user','callPartner'],
        callPartners:[],
        data(){
            return {
                localTracks:[],
                remoteUsers:{},
                app_id:"b5c8c3df2621406a80e2f2d616971085",
                token:"006b5c8c3df2621406a80e2f2d616971085IADfBGEyuOc0Xv2n/c1Ve2Ps/PWD1eFAjYevsBbhO5zarHlK1Y0AAAAAEACPl0pWeIbVYgEAAQB4htVi",
                channel:"MyAppChannel",
                UID:null,
                client:null,
            }
        },
        methods:{
           async endCall(){
               for(let i = 0; this.localTracks.length > i; i++){
                   this.localTracks[i].stop()
                   this.localTracks[i].close()
               }

               await this.client.leave();
               this.$emit('callEnded',this.callPartner.id)

           },
            async joinAndDisplayLocalStream(){
                this.UID=await this.client.join(this.app_id,this.channel,this.token,this.user.id);
                this.localTracks=await AgoraRTC.createMicrophoneAndCameraTracks();
                this.localTracks[1].play(`user-${this.UID}`)
                await this.client.publish([this.localTracks[0],this.localTracks[1]])

            },
            async handleUserJoin(user,mediaType){
                this.remoteUsers[user.uid]=user;
                await this.client.subscribe(user,mediaType);
                if(mediaType=='video'){
                    user.videoTrack.play(`user-${user.uid}`)
                }
                if(mediaType=='audio'){
                    user.audioTrack.play()
                }
            },
            async handleUserLeft(user){
                delete this.remoteUsers[user.uid]
            },
        },
       async created() {
           this.client=AgoraRTC.createClient({mode:'rtc', codec:'vp8'})
           await this.joinAndDisplayLocalStream()
            this.client.on('user-published', this.handleUserJoin)
            this.client.on('user-left', this.handleUserLeft)

        }


    }
</script>

<style scoped>
.video-container{
    width: 600px;
    heght:600px;
    border: 2px solid black;
}
.video-player{
    width: 400px;
    heght:400px;
    border: 2px solid black;
}
</style>
