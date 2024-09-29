<div>
    <div class="w-full h-screen overflow-hidden relative">
        <!-- Black Transparent Overlay -->
        <div class="absolute bg-black opacity-30 w-full h-full top-0 left-0"></div>
        <video autoplay loop muted class="absolute w-auto brightness-50 min-w-full min-h-full max-w-none">
            <source src="{{asset('video/1.mp4')}}" type="video/mp4"/>
        </video>
        <div class="relative z-20 max-w-screen-lg mx-auto grid grid-cols-12 h-full items-center px-2">
            <div class="md:col-span-6 col-span-12">
                <span class="uppercase text-white text-xs font-bold mb-2 block">WE ARE LISTENERS</span>
                <h1 class="text-white font-extrabold text-5xl mb-8"><span class="text-[#f5a425]">EM</span>PATHY provides Counseling in different ways</h1>
                <p class="text-stone-100 text-base">
                    Service helps people navigate difficult life situations, such as the death of a loved one, divorce, natural disasters, school stress, and the loss of a job.
                </p>
            </div>
        </div>
    </div>
    <div class="bg-[rgba(22,34,57,0.95)] py-20">
        <div class="max-w-screen-lg mx-auto md:flex justify-between items-center px-4">
            <div class="max-w-xl">
                <h2 class="font-black text-sky-50 text-3xl mb-4">As the leading experts in this field, we're in over 90 countries</h2>
                <p class="text-base text-sky-50 py-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            <button class="text-sky-50 uppercase py-3 text-base px-10 border border-sky-50 hover:bg-sky-50 hover:bg-opacity-10">Get started</button>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('header');
        const menuBtn = document.getElementById('menu-btn');
        const menu = document.getElementById('menu');

        // Sticky Header on Scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('sticky', 'top-0', 'shadow-md', 'z-50');
            } else {
                header.classList.remove('sticky', 'top-0', 'shadow-md', 'z-50');
            }
        });

        // Toggle Menu on Mobile
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    });
</script>
@endpush
