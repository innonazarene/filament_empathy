<div class="p-4">
    @if(false)
        <div class="flex flex-col">
            <div id="video-call-div" class="absolute top-0 left-0 w-full h-full">
                <video id="local-video" class="absolute top-0 left-0 m-5 rounded-lg bg-white" autoplay src=""></video>
                <video id="remote-video" class="bg-black w-full h-full" autoplay src=""></video>
            </div>
            <div class="call-action-div absolute left-[30%] md:left-[45%] bottom-[32px]">
                <button class="bg-gray-800 p-2 text-white" onclick="muteVideo()">Mute Video</button>
                <button class="bg-gray-800 p-2 text-white" onclick="muteAudio()">Mute Audio</button>
            </div>
        </div>
    @endif
    <div class="w-full p-2 text-center">
        <p class="md:text-4xl text-md font-bold md:font-light">
            ACTIVE LISTENERS HERE
        </p>
        <p class="text-md font-light text-center">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 md:flex md:gap-2 justify-center min-h-32" wire:poll.5s="updateOnlineUsers">
        @if (count($onlineUsers) > 0)
            @foreach ($onlineUsers as $user)
                <div class="bg-white font-semibold text-center rounded-3xl border shadow-lg p-4 md:min-w-[300px] md:max-w-[300px] min-w-[100%] max-w-[100%]">
                    @if ($user->avatar_url)
                        <img class="mb-3 w-32 h-32 rounded-full shadow-lg mx-auto" src="{{asset('storage/'.$user->avatar_url)}}" alt="profile-picture">
                    @else
                        <img class="mb-3 w-32 h-32 rounded-full shadow-lg mx-auto" src="{{asset('images/profile/default.png')}}" alt="profile-picture">
                    @endif
                    <h1 class="text-lg text-gray-700"> {{ ucfirst($user->name) }} </h1>
                    <h3 class="text-sm text-gray-400 ">{{ $user->custom_fields->designation_occupation ?? 'NO DESIGNATION' }}</h3>
                    <p class="text-xs text-gray-400 mt-4 min-h-[100px] max-h-[100px]"> {{ $user->custom_fields->biography ?? 'NO BIOGRAPHY' }}</p>
                    <button class="bg-green-600 px-8 py-2 mt-8 rounded-3xl text-gray-100 font-semibold uppercase tracking-wide">TALK</button>
                </div>
            @endforeach
        @else
            <div>
                <p class="text-2xl text-gray-500 flex flex-col justify-center">
                    NO USER ONLINE
                </p>
            </div>
        @endif

    </div>
</div>
