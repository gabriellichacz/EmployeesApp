<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Widok główny') }}
        </h2>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Widok główny') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 bg-white">
        <section class="py-10 bg-white">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/2 bg-gray-500 p-2 text-center"> Liczba pracowników:</div>
                <div class="w-full md:w-1/2 bg-gray-400 p-2 text-center text-gray-700">{{ $allEmployeeCount }}</div>
            </div>
            @foreach ($departments as $department => $employeeCount)
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/2 bg-gray-500 p-2 text-center"> Liczba pracowników działu {{ $department }}:</div>
                <div class="w-full md:w-1/2 bg-gray-400 p-2 text-center text-gray-700">{{ $employeeCount }}</div>
            </div>
            @endforeach
            
        </section>
    </div>

</x-app-layout>