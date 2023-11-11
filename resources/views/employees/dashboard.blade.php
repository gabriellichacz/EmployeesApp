<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pracownicy') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 bg-white">
        <section class="py-10 bg-white">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4 ">
                    <div class="max-w-full overflow-x-auto">
                        <table class="table table-bordered table-hover table-auto w-full data-table-big" id="data-table" class="display">
                            @include('employees.components.header')
                            @include('employees.components.table')
                            @include('employees.components.filters')
                        </table>
                        <div>
                            {{ $employees->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</x-app-layout>