@extends('user.app')

@section('content')

<div class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white text-[#3E382D]">

    <section class="relative h-[450px] flex items-center px-30 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?q=80&w=500" alt="Reflexology" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-white text-right">
            <div class="max-w-xl ml-auto">
                <h1 class="text-5xl text-bold mb-4">Reflexology</h1>
                <p class="text-sm leading-relaxed opacity-90 text-justify">
                    Reflexology adalah terapi pijat yang berfokus pada titik-titik tertentu di kaki yang terhubung dengan berbagai organ dalam tubuh. Melalui teknik tekanan yang tepat, terapi ini membantu melancarkan peredaran darah, mengurangi ketegangan, serta meningkatkan keseimbangan energi tubuh.
                </p>
            </div>
        </div>
    </section>

    <section class="py-16 container mx-auto px-6">
        <div class="flex items-center justify-center mb-10">
            <div class="flex-grow h-px bg-gray-300"></div>
            <h2 class="px-4 text-lg font-bold text-tertiary-500">APA SAJA MANFAATNYA?</h2>
            <div class="flex-grow h-px bg-gray-300"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <div class="border border-gray-400 bg-[#f5eaea] p-4 rounded-sm text-center">
                <p class="text-sm font-medium">Melancarkan peredaran darah</p>
            </div>
            <div class="border border-gray-400 bg-[#f5eaea] p-4 rounded-sm text-center">
                <p class="text-sm font-medium">Mengurangi stres & kelelahan</p>
            </div>
            <div class="border border-gray-400 bg-[#f5eaea] p-4 rounded-sm text-center">
                <p class="text-sm font-medium">Meningkatkan kualitas tidur</p>
            </div>
            <div class="border border-gray-400 bg-[#f5eaea] p-4 rounded-sm text-center">
                <p class="text-sm font-medium">Membantu meredakan pegal</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 max-w-2xl mx-auto mt-4">
            <div class="border border-gray-400 bg-[#f5eaea] p-4 rounded-sm text-center">
                <p class="text-sm font-medium">Menyeimbangkan energi tubuh</p>
            </div>
            <div class="border border-gray-400 bg-[#f5eaea] p-4 rounded-sm text-center">
                <p class="text-sm font-medium">Memberikan relaksasi menyeluruh</p>
            </div>
        </div>
    </section>

    <section class="py-12 bg-pink-50/50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-center mb-12">
                <div class="flex-grow h-px bg-gray-300"></div>
                <h2 class="px-4 text-lg font-bold text-tertiary-500 uppercase ">Reflexology Treatment</h2>
                <div class="flex-grow h-px bg-gray-300"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100">
                    <img src="foot-reflex.jpg" class="w-full h-56 object-cover" alt="Foot Reflexology">
                    <div class="p-6">
                        <span class="text-[10px] bg-rose-100 text-rose-500 px-2 py-1 rounded-full uppercase font-bold">Reflexology</span>
                        <div class="flex justify-between items-start mt-2">
                            <h3 class="text-xl font-bold">Foot Reflexology</h3>
                            <span class="text-xs text-gray-400 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                60 min
                            </span>
                        </div>
                        <p class="text-tertiary-500 text-xs mt-2 line-clamp-2 italic">Membantu meredakan pegal dan meningkatkan sirkulasi darah</p>
                        <div class="mt-6 flex justify-between items-center border-t pt-4">
                            <span class="font-bold text-tertiary-500 uppercase">Rp. 45.000</span>
                            <button class="bg-rose-200 text-rose-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-rose-300 transition">Book Now</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100">
                    <img src="creambath.jpg" class="w-full h-56 object-cover" alt="Foot Reflexology + Creambath">
                    <div class="p-6">
                        <span class="text-[10px] bg-rose-100 text-rose-500 px-2 py-1 rounded-full uppercase font-bold">Reflexology</span>
                        <div class="flex justify-between items-start mt-2">
                            <h3 class="text-xl font-bold">Foot Reflexology <br>+ Creambath</h3>
                            <span class="text-xs text-gray-400 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                90 min
                            </span>
                        </div>
                        <p class="text-tertiary-500 text-xs mt-2 italic">Pijat relaksasi untuk kaki, perawatan terbaik untuk rambut.</p>
                        <div class="mt-6 flex justify-between items-center border-t pt-4">
                            <span class="font-bold text-tertiary-500 uppercase">Rp. 80.000</span>
                            <button class="bg-rose-200 text-rose-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-rose-300 transition">Book Now</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100">
                    <img src="hair-spa.jpg" class="w-full h-56 object-cover" alt="Foot Reflexology + Hair Spa">
                    <div class="p-6">
                        <span class="text-[10px] bg-rose-100 text-rose-500 px-2 py-1 rounded-full uppercase font-bold">Reflexology</span>
                        <div class="flex justify-between items-start mt-2">
                            <h3 class="text-xl font-bold">Foot Reflexology <br>+ Hair Spa</h3>
                            <span class="text-xs text-gray-400 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                90 min
                            </span>
                        </div>
                        <p class="text-tertiary-500 text-xs mt-2 italic">Rilekskan tubuh, segarkan rambut.</p>
                        <div class="mt-6 flex justify-between items-center border-t pt-4">
                            <span class="font-bold text-tertiary-500 uppercase">Rp. 110.000</span>
                            <button class="bg-rose-200 text-rose-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-rose-300 transition">Book Now</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection