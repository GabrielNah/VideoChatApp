<template>
    <div v-cloak class="mt-3 w-75 ">
        <h1>{{callPartner.name}}</h1>
        <div class="d-flex flex-row w-100 ">
            <div class="video-container w-50 border-success" :id="'user-container'+user.id">
                <div class="video-player w-100 " :id="'user-'+user.id"></div>
            </div>
            <div class="video-container w-50 border-dark" :id="'user-container'+remoteUser.uid" v-for="remoteUser in callPartners">
                <div class="video-player w-100 " :id="'user-'+remoteUser.uid"></div>
            </div>
        </div>
        <button class="btn btn-danger" @click="endCall">End Call</button>
    </div>
</template>

<script>
    export default {
        name: "VideoCallRoom",
        props:['user','callPartner','token'],
        data(){
            return {
                localTracks:[],
                callPartners:[],
                app_id:"b5c8c3df2621406a80e2f2d616971085",
                channel:"MyAppChannel",
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
                this.client.on('user-published', this.handleUserJoin);
                this.client.on('user-left', this.handleUserLeft)

               await this.client.join(this.app_id,this.channel,this.token,this.user.id);
                this.localTracks=await AgoraRTC.createMicrophoneAndCameraTracks();
                this.localTracks[1].play(`user-${this.user.id}`)
                await this.client.publish(this.localTracks)
            },
            async handleUserJoin(user,mediaType){


                for(let i=0;i<this.client.remoteUsers.length;i++) {

                    if(!this.checkIfTheUsersPartIsExists(user)){
                        await this.client.subscribe(this.client.remoteUsers[i], mediaType)
                        this.callPartners=[];
                        this.callPartners.push(this.client.remoteUsers[i])
                    }
                    // If the subscribed track is an audio track
                    if (mediaType === "audio") {
                        const audioTrack = user.audioTrack;
                        // Play the audio
                        audioTrack.play();
                    } else {
                        let player = document.getElementById(`user-container-${user.uid}`)
                        if (player != null){
                            player.remove()
                        }
                        const videoTrack = user.videoTrack;
                        // Play the video
                        videoTrack.play(`user-${user.uid}`);
                    }
                }
            },
            checkIfTheUsersPartIsExists(user){
                this.callPartners.some(item=>JSON.stringify(item)==JSON.stringify(user))
            },
            getRemoteUsersIndex(userData){
                let userIndex;
                this.callPartners.forEach((item,index)=>{
                    if(JSON.stringify(userData)==JSON.stringify(item)){
                        userIndex=index;
                    }
                })
                return userIndex;
            },
            async handleUserLeft(user){
                for(let i = 0; this.localTracks.length > i; i++){
                    this.localTracks[i].stop()
                    this.localTracks[i].close()
                }

                await this.client.leave()
                let indexOfRemovableUser=this.getRemoteUsersIndex(user);
                this.remoteUsers.splice(indexOfRemovableUser,1)
            },
            async joinStream(){
                await this.joinAndDisplayLocalStream()
            }
        },
        created() {
            this.client=AgoraRTC.createClient({mode:'rtc', codec:'vp8'})
        },
        mounted() {
            this.joinStream()

        }
    }
</script>

<style scoped>

    .video-player{
        height:600px !important;

    }

</style>
