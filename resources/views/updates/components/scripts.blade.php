<script>
    $(min_salary).val({{$filters['min_salary'] ?? ''}});
    $(max_salary).val({{$filters['max_salary'] ?? ''}});

    $('input[name=raise_perc]').on('change', function() {
        if(this.value.length > 0) {
            $('input[name=raise_const]').attr('disabled','disabled');
            $('input[name=job_title]').attr('disabled','disabled');
        }   
        else {
            $('input[name=raise_const]').removeAttr('disabled');
            $('input[name=job_title]').attr('disabled','disabled');
        }
    });


    $('input[name=raise_const]').on('change', function() {
        if(this.value.length > 0) {
            $('input[name=raise_perc]').attr('disabled','disabled');
            $('input[name=job_title]').attr('disabled','disabled');
        }
        else {
            $('input[name=raise_perc]').removeAttr('disabled');
            $('input[name=job_title]').attr('disabled','disabled');
        }
    });

    $('input[name=job_title]').on('change', function() {
        if(this.value.length > 0) {
            $('input[name=raise_perc]').attr('disabled','disabled');
            $('input[name=raise_const]').attr('disabled','disabled');
        }
        else {
            $('input[name=raise_perc]').removeAttr('disabled');
            $('input[name=raise_const]').attr('disabled','disabled');
        }
    });
</script>