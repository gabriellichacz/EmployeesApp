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