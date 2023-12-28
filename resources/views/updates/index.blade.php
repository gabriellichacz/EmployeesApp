<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aktualizacje pracowników') }}
        </h2>
        @if (session('alert'))
            <div class="alert alert-success">
                {{ session('alert') }}
            </div>
        @endif
    </x-slot>

    <div class="container mx-auto px-4 bg-white">
        <section class="py-10 bg-white">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4 ">
                    <div class="max-w-full overflow-x-auto">
                        <table class="table table-bordered table-hover table-auto w-full data-table-big" id="data-table" class="display">
                            @include('updates.components.table')
                            @include('updates.components.footer')
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

</x-app-layout>
@include('updates.components.scripts')
