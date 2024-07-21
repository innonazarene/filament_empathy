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
