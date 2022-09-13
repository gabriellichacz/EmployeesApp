<!-- Table -->
<div class="container mx-auto px-4 bg-white">
    <section class="py-10 bg-white">
        
        <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4 ">
                <div class="max-w-full overflow-x-auto">
                    <table class="table table-bordered table-hover table-auto w-full data-table-big" id="data-table" class="display">
                        <thead>
                            <tr class="bg-primary text-center bg-[#F3F6FF]">
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('Employee ID') }}
                                </th>
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('First name') }}
                                </th>
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('Last name') }}
                                </th>
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('Departament') }}
                                </th>
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('Title') }}
                                </th>
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('Salary') }}
                                </th>
                                <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    {{ __('Export') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tbody>
                                @if (sizeof($employees) > 0)
                                    <form id="exportForm" action="/export" enctype="multipart/form-data" method="post">
                                        @csrf
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                    {{ $employee->emp_no }}
                                                </td>
                                                <td class=" text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                {{ $employee->first_name }}
                                                </td>
                                                <td class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                    {{ $employee->last_name }}
                                                </td>
                                                <td class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                    {{ $employee->dept_name }}
                                                </td>
                                                <td class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                    {{ $employee->title }}
                                                </td>
                                                <td class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                    {{ $employee->salary }}
                                                </td>
                                                <td class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                                    <input id="checkboxExport" name="checkboxExport[]" type="checkbox" value="{{ $employee->emp_no }}" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>    
                                        @endforeach
                                    </form>
                                @else
                                    <tr>
                                        {{ __('Database connection error') }}
                                    </tr>
                                @endif
                            </tbody>
                        </tbody>
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
                                                {{ __('Filter salary') }}
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
                                                {{ __('Filter gender') }}
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
                                                {{ __('Filter status') }}
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
                                                {{ __('Filter departments') }}
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
                    </table>
                    <div>
                        {{ $employees->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>

    </section>
</div>
<!-- end of Table -->