<div>
    <style> [x-cloak] { display: none !important; } </style>
        <div class="w-full" wire:poll.2s="checkIfUserPeerExist()"></div>
    <div class="p-4">
        <div class="w-full p-2 text-center">
            <p class="md:text-4xl text-md font-bold md:font-light">
                ACTIVE LISTENERS HERE
            </p>
            <p class="text-md font-light text-center">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 md:flex md:gap-2 justify-center min-h-32" wire:poll.15s="updateOnlineUsers()">
            @if (count($onlineUsers) > 0)
                @foreach ($onlineUsers as $user)
                    <div class="bg-white font-semibold text-center rounded-3xl border shadow-lg p-4 md:min-w-[300px] md:max-w-[300px] min-w-[100%] max-w-[100%]">
                        @if ($user->avatar_url)
                            <img class="mb-3 w-32 h-32 rounded-full shadow-lg mx-auto" src="{{ asset('storage/' . $user->avatar_url) }}" alt="profile-picture">
                        @else
                            <img class="mb-3 w-32 h-32 rounded-full shadow-lg mx-auto" src="{{ asset('images/profile/default.png') }}" alt="profile-picture">
                        @endif
                        <h1 class="text-lg text-gray-700"> {{ ucfirst($user->name) }} </h1>
                        <h3 class="text-sm text-gray-400 ">{{ $user->custom_fields->designation_occupation ?? 'NO DESIGNATION' }}</h3>
                        <p class="text-xs text-gray-400 mt-4 min-h-[100px] max-h-[100px]"> {{ $user->custom_fields->biography ?? 'NO BIOGRAPHY' }}</p>
                        <button x-on:click="createSignal({{$user->id}})" class="bg-green-600 px-8 py-2 mt-8 rounded-3xl text-gray-100 font-semibold uppercase tracking-wide">TALK</button>
                    </div>
                @endforeach
            @else
                <div class="flex justify-center">
                    <p class="text-2xl text-gray-500 m-auto">
                        NO USER ONLINE
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div x-data x-show="modal.show" x-cloak class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center">
        <div x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="transform scale-75" x-transition:enter-end="transform scale-100" x-transition:leave="transition-transform ease-in duration-300" x-transition:leave-start="transform scale-100" x-transition:leave-end="transform scale-75" class="bg-white rounded-md shadow-xl overflow-hidden md:w-[90%] w-full relative">
            <!-- Modal Body -->
            <div class="flex flex-col h-[400px] md:h-full">
                <div id="video-call-div" class="w-full h-full">
                    <video id="local-video-guest" class="absolute top-0 left-0 m-2  w-[130px] rounded-lg bg-white" autoplay src=""></video>
                    <video id="remote-video-guest" class="bg-black w-full h-[300px] md:w-[500px] md:h-[500px]" autoplay src=""></video>
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
        let modal = Alpine.reactive({
            show: false
        });
        let peerConnection;
        let localStream;
        let remoteStream;
        let audioMuted = false;
        let videoMuted = false;

        //create signal
        let createSignal = async (id) => {
            await initialize();
            let localOffer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(localOffer);

            await new Promise((resolve) => {
                peerConnection.onicegatheringstatechange = () => {
                    if (peerConnection.iceGatheringState === 'complete') {
                        resolve();
                    }
                };
            });

            let offer = JSON.stringify(peerConnection.localDescription);
            await @this.call('createOfferSession', offer, id);
            modal.show = true;
            AcceptAnswer();
        }

        let initialize = async () => {
            peerConnection = new RTCPeerConnection();
            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            remoteStream = new MediaStream();
            document.getElementById('local-video-guest').srcObject = localStream;
            document.getElementById('remote-video-guest').srcObject = remoteStream;
            document.getElementById('local-video-guest').muted = true;

            localStream.getTracks().forEach((track) => {
                peerConnection.addTrack(track, localStream);
            });

            peerConnection.ontrack = (event) => {
                event.streams[0].getTracks().forEach((track) => {
                    remoteStream.addTrack(track);
                });
            };
        }

        let AcceptAnswer = () => {
            let process = true;
            setInterval(async () => {
                let answer = JSON.parse(@this.answer);
                if (!peerConnection.currentRemoteDescription && process && answer) {
                    await peerConnection.setRemoteDescription(answer);
                    await @this.call('createVideoSessionLog');
                    process = false;
                }
            }, 1000);
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
            window.location.href = '/';
        }
    </script>
    @endpush
</div>

