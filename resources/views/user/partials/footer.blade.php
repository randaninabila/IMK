<footer class="bg-[#3E382D] text-[#F3EFE0] py-6 px-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 items-start">

    {{-- BRAND --}}
    <div class="flex gap-4 md:col-span-1 items-start">
        
        {{-- LOGO --}}
        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white/20 flex-shrink-0 bg-white">
            <img
                src="{{ $salon->logo }}"
                alt="{{ $salon->nama }}"
                class="w-full h-full object-cover"
            >
        </div>

        {{-- TEXT --}}
        <div>
            <h3 class="text-2xl font-semibold leading-snug">
                {{ $salon->nama }}
            </h3>

            <p class="mt-3 text-sm leading-relaxed opacity-80">
                {{ $salon->tagline }}
            </p>
        </div>

    </div>

        {{-- CABANG — loop dari DB --}}
        @foreach($cabangList as $cabang)
        <div>
            <h4 class="font-bold tracking-widest text-sm mb-4 uppercase">
                {{ $cabang->nama_cabang }}
            </h4>
            <div class="space-y-2 text-sm opacity-80 leading-relaxed">
                {{-- Alamat --}}
                <div class="flex gap-2 items-start">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $cabang->alamat }}</span>
                </div>

                {{-- Jam operasional --}}
                @if($cabang->jam_buka && $cabang->jam_tutup)
                <div class="flex gap-2 items-center">
                    <svg class="w-4 h-4 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        Senin–Minggu,
                        {{ \Carbon\Carbon::parse($cabang->jam_buka)->format('H:i') }}–{{ \Carbon\Carbon::parse($cabang->jam_tutup)->format('H:i') }} WIB
                    </span>
                </div>
                @endif

                {{-- Status --}}
                <div class="flex gap-2 items-center">
                    <span class="w-2 h-2 rounded-full {{ $cabang->status === 'BUKA' ? 'bg-green-400' : 'bg-red-400' }} flex-shrink-0"></span>
                    <span class="text-xs font-semibold {{ $cabang->status === 'BUKA' ? 'text-green-300' : 'text-red-300' }}">
                        {{ $cabang->status }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach

        {{-- CONTACT + SOCIAL --}}
        <div>
            <h4 class="font-bold tracking-widest text-sm mb-4 uppercase">Follow Us</h4>
            <div class="flex gap-4 items-center mb-6">
                <a href="#" class="hover:opacity-75 transition-opacity" aria-label="Instagram">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <a href="#" class="hover:opacity-75 transition-opacity" aria-label="WhatsApp">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.655zm6.222-3.432l.35.207c1.493.886 3.209 1.353 4.965 1.354 5.383 0 9.765-4.382 9.767-9.764.001-2.607-1.013-5.059-2.859-6.905-1.845-1.845-4.294-2.86-6.903-2.861-5.384 0-9.765 4.383-9.767 9.765-.001 1.834.512 3.616 1.483 5.158l.228.361-1.001 3.652 3.737-.98z"/>
                    </svg>
                </a>
            </div>

            <h4 class="font-bold tracking-widest text-sm mb-3 uppercase">Hubungi Kami</h4>
            <div class="flex gap-2 items-center text-sm opacity-80">
                <svg class="w-4 h-4 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span>{{ $salon->no_telepon }}</span>
            </div>
        </div>

    </div>

    {{-- Bottom bar --}}
    <div class="max-w-7xl mx-auto mt-12 pt-6 border-t border-white/10 text-center text-xs opacity-50">
        © {{ date('Y') }} Salon Muslimah Dina. All rights reserved.
    </div>
</footer>