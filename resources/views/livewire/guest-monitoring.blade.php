<div class="ml-48 pt-2">
    <style> [x-cloak] { display: none !important; } </style>
    <div wire:poll.2s="getSessionGuestCount()">
        @if ($currentSession)
            <div class="w-full bg-gray-800 p-4 shadow-lg flex gap-2">
                <h1>
                    INCOMMING ALLERT
                </h1>
                <div class="flex justify-end gap-2">
                    <button x-on:click.prevent="createAnswerSession({{$offer}})" class="bg-green-600 hover:bg-green-500 p-4">ACCEPT</button>
                    <button class="bg-green-600 hover:bg-green-500 p-4">REJECT</button>
                </div>
            </div>
        @else
            <div class="w-full bg-gray-100 p-4 shadow-lg flex gap-2">
                <h1 class="text-gray-900">
                    NO GUEST
                </h1>
            </div>
        @endif
    </div>
    <div x-data x-show="modal.show" x-cloak class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center">
        <div x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="transform scale-75" x-transition:enter-end="transform scale-100" x-transition:leave="transition-transform ease-in duration-300" x-transition:leave-start="transform scale-100" x-transition:leave-end="transform scale-75" class="bg-white rounded-md shadow-xl overflow-hidden md:w-[90%] w-full relative">
            <!-- Modal Body -->
            <div class="flex flex-col h-[400px] md:h-full">
                <div id="video-call-div" class="w-full h-full">
                    <video id="local-video-user" class="absolute top-0 left-0 m-2  w-[130px] rounded-lg bg-white" autoplay src=""></video>
                    <video id="remote-video-user" class="bg-black w-full h-[300px] md:w-[500px] md:h-[500px]" autoplay src=""></video>
                </div>
                <div class="call-action-div absolute left-[30%] md:left-[43%] bottom-[32px]" wire:ignore>
                    <button id="mute-audio-btn" class="bg-gray-800 p-2 text-white" onclick="muteAudio()">Mute Audio</button>
                    <button id="mute-video-btn" class="bg-gray-800 p-2 text-white" onclick="muteVideo()">Close Video</button>
                    <button id="mute-video-btn" class="bg-gray-800 p-2 text-white" onclick="exitCall()">Exit</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            let peerConnection;
            let localStream;
            let remoteStream;
            let audioMuted = false;
            let videoMuted = false;

            Livewire.on('calling_audio', () => {
                // var audio = new Audio('{{asset('audio/call.m4a')}}');
            });

            let modal = Alpine.reactive({
                show: false
            });

            let initialize = async () => {
                peerConnection = new RTCPeerConnection();
                localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                remoteStream = new MediaStream();
                document.getElementById('local-video-user').srcObject = localStream;
                document.getElementById('remote-video-user').srcObject = remoteStream;
                document.getElementById('local-video-user').muted = true;

                localStream.getTracks().forEach((track) => {
                    peerConnection.addTrack(track, localStream);
                });
                peerConnection.ontrack = (event) => {
                    event.streams[0].getTracks().forEach((track) => {
                        remoteStream.addTrack(track);
                    });
                };
            }
            let createAnswerSession = async (offer) => {
                await initialize();
                modal.show = true;
                peerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        console.log('Adding answer candidate:', event.candidate);
                    }
                };
                await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
                let answerLocal = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answerLocal);
                await new Promise((resolve) => {
                    peerConnection.onicegatheringstatechange = () => {
                        if (peerConnection.iceGatheringState === 'complete') {
                            resolve();
                        }
                    };
                });
                let answer = JSON.stringify(peerConnection.localDescription);
                await @this.call('createAnswerSession', answer);
            }
            // Mute audio
            let muteAudio = () => {
                audioMuted = !audioMuted;
                localStream.getAudioTracks().forEach(track => track.enabled = !audioMuted);
                document.getElementById('mute-audio-btn').textContent = audioMuted ? 'Unmute Audio' : 'Mute Audio';
            }
            // Mute video
            let muteVideo = () => {
                videoMuted = !videoMuted;
                localStream.getVideoTracks().forEach(track => track.enabled = !videoMuted);
                document.getElementById('mute-video-btn').textContent = videoMuted ? 'Open Video' : 'Close Video';
            }
            // Hang up
            let hangUp = () => {
                peerConnection.close();
                localStream.getTracks().forEach(track => track.stop());
                modal.show = false;
            }
            // Exit
            let exitCall = () => {
                hangUp();
                window.location.href = '/admin/guest-monitoring';
            }
        </script>
    @endpush
</div>
