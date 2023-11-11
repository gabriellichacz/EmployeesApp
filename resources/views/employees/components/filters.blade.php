<tfoot>
    <tr class="bg-primary text-center bg-[#F3F6FF]">
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <form action="/filter" enctype="multipart/form-data" method="get">
                @csrf
                <div class="row justify-content-center">
                    <div class="form-group row mb-2">
                        <input type="text" id="min_salary" name="min_salary" placeholder="Minimum salary">
                    </div>
                    <div class="form-group row mb-2">
                        <input type="text" id="max_salary" name="max_salary" placeholder="Maximum salary">
                    </div>
                    <button type="submit" class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"> 
                        {{ __('Filtruj wynagrodzenie') }}
                    </button>
                </div>
            </form>
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <form action="/filter" enctype="multipart/form-data" method="get">
                @csrf
                <div class="row justify-content-center">
                    <div class="form-group row">
                        <select name="gender" id="gender">
                            <option value="A" selected> {{ __('All') }} </option>
                            <option value="M"> {{ __('Male') }} </option>
                            <option value="F"> {{ __('Female') }} </option>
                        </select>
                    </div>
                    <button type="submit" class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"> 
                        {{ __('Filtruj płeć') }}
                    </button>
                </div>
            </form>
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <form action="/filter" enctype="multipart/form-data" method="get">
                @csrf
                <div class="row justify-content-center">
                    <div class="form-group row">
                        <select name="status" id="status">
                            <option value="all" selected> {{ __('All employees' )}} </option>
                            <option value="working"> {{ __('Currently working' ) }} </option>
                            <option value="former">  {{ __('Former employees' ) }} </option>
                        </select>
                    </div>
                    <button type="submit" class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"> 
                        {{ __('Filtruj status') }}
                    </button>
                </div>
            </form>
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <form action="/filter" enctype="multipart/form-data" method="get">
                <div class="row justify-content-center">
                    <div class="form-group row">
                        <select name="department" id="department">
                            @if (sizeof($dep_names) > 0)
                                <option value="all" selected> {{ __('All departments') }} </option>
                                @foreach($dep_names as $key => $dep_name)
                                    <option value="{{ $dep_name }}"> {{ $dep_name }} </option>
                                @endforeach
                            @else
                                <option value="all" selected> {{ __('Database connection error') }} </option>
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"> 
                        {{ __('Filtruj dział') }}
                    </button>
                </div>
            </form>
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <input type="submit" form="exportForm" value="Export chosen data" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"/>
        </th>
    </tr>
</tfoot>