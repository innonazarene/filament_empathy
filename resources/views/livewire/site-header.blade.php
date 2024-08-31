<div>
    <header id="header" class="bg-white w-full transition-all duration-300 h-100 flex justify-between bg-[rgba(22,34,57,0.95)] text-white tracking-wide">
        <nav class="container mx-auto p-4 flex justify-between items-center">
            <p class="text-3xl font-bold ml-4"><span class="text-[#f5a425]">EM</span>PATHY</p>
            <ul class="flex space-x-2 mr-4">
                <li>
                    <a href="#about" class="border-2 border-transparent px-4 py-2 transition-colors duration-300 hover:border-[#f5a425] focus:border-[#f5a425] active:border-[#f5a425]">
                        ABOUT US
                    </a>
                </li>
                <li>
                    <a href="#contact" class="border-2 border-transparent px-4 py-2 transition-colors duration-300 hover:border-[#f5a425] focus:border-[#f5a425] active:border-[#f5a425]">
                        CONTACT US
                    </a>
                </li>
                <li>
                    <a href="#advertisements" class="border-2 border-transparent px-4 py-2 transition-colors duration-300 hover:border-[#f5a425] focus:border-[#f5a425] active:border-[#f5a425]">
                        ADVERTISEMENT
                    </a>
                </li>
                <li>
                    <a href="#patrons" class="border-2 border-transparent px-4 py-2 transition-colors duration-300 hover:border-[#f5a425] focus:border-[#f5a425] active:border-[#f5a425]">
                        PATRONS
                    </a>
                </li>
                <li>
                    <a href="#listeners" class="border-2 border-transparent px-4 py-2 transition-colors duration-300 hover:border-[#f5a425] focus:border-[#f5a425] active:border-[#f5a425]">
                        LISTENERS
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <div class="w-full h-screen overflow-hidden relative before:block before:absolute before:bg-black before:h-full before:w-full before:top-0 before:left-0 before:opacity-30">
        <!-- Black Transparent Overlay -->
        <div class="absolute bg-black opacity-30 w-full h-full top-0 left-0"></div>
        <video autoplay loop muted
            class="absolute w-auto brightness-50
            min-w-full min-h-full max-w-none">
            <source src=
                "{{asset('video/1.mp4')}}"
                type="video/mp4"/>
        </video>
        <div class="relative z-20 max-w-screen-lg mx-auto grid grid-cols-12 h-full items-center px-2">
            <div class="md:col-span-6 col-span-12">
                <span class="uppercase text-white text-xs font-bold mb-2 block">WE ARE LISTENERS</span>
                <h1 class="text-white font-extrabold text-5xl mb-8">Emphaty provides Counseling in different ways</h1>
                <p class="text-stone-100 text-base">
                    Service helps people navigate difficult life situations, such as the death of a loved one, divorce, natural disasters, school stress, and the loss of a job.
                </p>
            </div>
        </div>
    </div>
    <div class="bg-[#24a263] py-20">
        <div class="max-w-screen-lg mx-auto md:flex justify-between items-center px-4">
            <div class="max-w-xl">
                <h2 class="font-black text-sky-50 text-3xl mb-4">As the leading experts in this field, we're in over 90 countries</h2>
                <p class="text-base text-sky-50 py-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            <button class="text-sky-50 uppercase py-3 text-base px-10 border border-sky-50 hover:bg-sky-50 hover:bg-opacity-10">Get started</button>
        </div>
    </div>
    <div class="py-4 relative overflow-hidden bg-white">
        <div class="grid md:grid-cols-2 grid-cols-1 max-w-screen-lg mx-auto">
            <div class="w-full flex flex-col items-end pr-16 pb-4">
                <h2 class="text-[#24a263] font-bold text-2xl max-w-xs text-right mb-12 mt-10">Whether you need Assistance</h2>
                <div class="h-full mt-auto overflow-hidden relative">
                    <img src="{{asset('images/other/counseling.png')}}" class="h-full w-full object-cover" alt="">
                </div>
            </div>
            <div class="py-20 bg-slate-100 relative before:absolute before:h-full before:w-screen before:bg-[#24a263] before:top-0 before:left-0">
                <div class="relative z-20 md:pl-12 px-4">
                    <h2 class="text-[#e7f4ed] font-black text-5xl leading-snug mb-10">Emphaty is here <br>to help you</h2>
                    <p class="text-white text-sm">
                        Purus in massa tempor nec. Magna etiam tempor orci eu lobortis elementum nibh tellus molestie. Faucibus ornare suspendisse sed nisi lacus sed viverra. Diam in arcu cursus euismod quis viverra nibh cras pulvinar.
                    </p>
                    <button class="mt-8 text-white uppercase py-3 text-sm px-10 border border-white hover:bg-white hover:bg-opacity-10">Talk with LISTENERS
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('sticky', 'top-0', 'shadow-md', 'z-50');
            } else {
                header.classList.remove('sticky', 'top-0', 'shadow-md', 'z-50');
            }
        });
    });
</script>
@endpush
