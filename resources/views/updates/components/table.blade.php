<tbody>
    <tbody>
        <form action="/updates/rule/add" enctype="multipart/form-data" method="get">
            <tr class="bg-primary text-center bg-[#F3F6FF]">
                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                    <div class="row justify-content-center">
                        <div class="form-group row mb-2">
                            <input type="text" id="min_salary" name="min_salary" placeholder="Min płaca">
                        </div>
                        <div class="form-group row mb-2">
                            <input type="text" id="max_salary" name="max_salary" placeholder="Max płaca">
                        </div>
                    </div>
                </th>
                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent"> 
                    <div class="row justify-content-center">
                        <div class="form-group row">
                            <select name="gender" id="gender">
                                <option value="A"> {{ __('Wszyscy') }} </option>
                                <option value="M"> {{ __('Mężczyźni') }} </option>
                                <option value="F"> {{ __('Kobiety') }} </option>
                            </select>
                        </div>
                    </div>
                </th>
                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                    <div class="row justify-content-center">
                        <div class="form-group row">
                            <select name="status" id="status">
                                <option value="all"> {{ __('Wszyscy pracownicy' )}} </option>
                                <option value="working"> {{ __('Obecni pracownicy' ) }} </option>
                                <option value="former">  {{ __('Byli pracownicy' ) }} </option>
                            </select>
                        </div>
                    </div>
                </th>
                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                    <div class="row justify-content-center">
                        <div class="form-group row">
                            <select name="department" id="department">
                                @if (sizeof($dep_names) > 0)
                                    <option value="all"> {{ __('Wszystkie działy') }} </option>
                                    @foreach($dep_names as $key => $dep_name)
                                        <option value="{{ $dep_name }}"> {{ $dep_name }} </option>
                                    @endforeach
                                @else
                                    <option value="all" selected> {{ __('Problem połączenia z bazą danych') }} </option>
                                @endif
                            </select>
                        </div>
                    </div>
                </th>
                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                </th>
                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                    <button type="submit" class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"> 
                        {{ __('Dodaj regułę aktualizacji pracowników') }}
                    </button>
                </th>
            </tr>
        </form>
    </tbody>
</tbody>