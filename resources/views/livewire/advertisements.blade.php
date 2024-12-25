<div class="w-full">
    <div class="p-4 w-full flex justify-center">
        <p class="font-bold text-md border-b-2 border-b-gray-700">
            Advertisements & Sponsors
        </p>
    </div>
    <div class="p-2 grid grid-rows-1 gap-5 text-center">
            @forelse ($advertisements as $item)
                <div class="w-full relative cursor-pointer text-white">
                    <div class="absolute bottom-5 pl-5">
                        <p class="text-lg">{{ $item->ad_title ?? "no-title" }} </p>
                        <p>{{ substr($item->ad_description ?? "", 0, 50) }}</p>
                    </div>
                    @if($item->ad_image)
                        <img class="w-full h-[220px] object-cover" src="{{ url('/storage/'.$item->ad_image[0]) }}" alt="Img 1" id="img1" />
                    @endif
                </div>
            @empty
                <p class="text-center">NO DATA</p>
            @endforelse
    </div>
</div>
