@extends('user.app')

@section('content')

<section class="min-h-screen bg-gradient-to-b from-[#FFE4E6] to-white flex flex-col items-center py-12 px-4">
    
    <h1 class="text-5xl md:text-6xl font-serif font-bold text-black mb-12 mt-16">
        Dr. Zahra Khairunnisa
    </h1>

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-sm flex flex-col md:flex-row overflow-hidden border border-gray-100">
        
        <div class="w-full md:w-2/5 h-56 md:h-auto overflow-hidden">
            <img 
                src="path/to/your/specialist-image.jpg" 
                alt="Dr. Zahra Khairunnisa" 
                class="w-full h-full object-cover"
            />
        </div>

        <div class="w-full md:w-3/5 p-6 md:p-6 flex flex-col justify-between">
            
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Dr. Zahra Khairunnisa</h2>
                    <p class="text-gray-500 text-sm">Aesthetic Specialist</p>
                </div>

                <div class="text-right">
                    <div class="inline-flex items-center gap-2 bg-[#FFB3B3] text-white px-4 py-1.5 rounded-md text-sm font-medium mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Available Hours
                    </div>
                    <div class="text-[10px] text-white space-y-1">
                        <p class="bg-[#FFB3B3] px-2 py-1 rounded">Monday - Friday (10.00 s/d 16.00)</p>
                        <p class="bg-[#FFB3B3] px-2 py-1 rounded">Saturday - Sunday (12.00 s/d 16.00)</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-700 text-sm leading-relaxed mb-2 max-w-sm">
                Focused on providing personalized beauty treatments with modern techniques for balanced and natural-looking results.
            </p>

            <ul class="space-y-2 mb-2">
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Skin Treatment
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Skin Brightening
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Acne Care
                </li>
            </ul>

            <div class="flex flex-col gap-4">
                <div class="text-[10px] text-gray-500 italic">
                    For a specific time that not on the list, please contact phone number below<br>
                    <span class="font-semibold text-gray-700">0812-3456-7890</span>
                </div>
                
                <button class="w-full bg-[#E5B8B8] hover:bg-[#D4A7A7] text-white font-bold py-2 rounded-lg transition-colors text-xl shadow-inner">
                    Book Now
                </button>
            </div>
        </div>
    </div>
</section>

@endsection