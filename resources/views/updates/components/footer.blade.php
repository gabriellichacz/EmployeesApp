<tfoot>
    <tr class="bg-primary text-center bg-[#F3F6FF]">
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            {{ __('Pensja') }}
        </th>
    </tr>
    <tr class="bg-primary text-center bg-[#F3F6FF]"> 
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <input type="text" form="updateForm" id="raise_perc" name="raise_perc" placeholder="Podwyżka procentowa">
        </th>
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <input type="text" form="updateForm" id="raise_const" name="raise_const" placeholder="Podwyżka stała">
        </th>
    </tr>
    <tr class="bg-primary text-center bg-[#F3F6FF]">
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            {{ __('Stanowisko') }}
        </th>
    </tr>
    <tr class="bg-primary text-center bg-[#F3F6FF]"> 
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <input type="text" form="updateForm" id="job_title" name="job_title" placeholder="Stanowisko">
        </th>
    </tr>
    <tr class="bg-primary text-center bg-[#F3F6FF]"> 
        <th class="w-1/6 min-w-[160px] text-lg font-semibold text-black py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
            <input type="submit" form="updateForm" value="Aktualizuj pracowników" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"/>
        </th>
    </tr>
</tfoot>
