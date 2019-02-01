{{--<script>--}}

    var updateSelectors = function(){
        $('#school-select').prop('disabled', $('#city-select').val() == 'all');
        $('#class-select').prop('disabled', $('#school-select').val() == 'all');
    };

    $(function(){
        $('#filtering').get(0).reset();
        updateSelectors();
        $('#city-select').change();
        $('#school-select').change();
    });

    $('#city-select').change(function(){
        var cityId = $(this).val();
        if(!$.isNumeric(cityId)) {
            cityId = 0;
        }
        $.ajax({
            type: "GET",
            url: "{{route('classfiltering.get.schools')}}/" + cityId,
            dataType: 'json',
            success: function( rst ) {
                var schools = rst.data;
                $('#school-select').find('option[value!="all"]').remove();
                $('#class-select').find('option[value!="all"]').remove();
                $.each( schools, function( key, school ) {
                    $('#school-select').append("<option value='"+ school.id +"'>" + school.name + "</option>");
                    var classes = school.classes;
                    $.each( classes, function( key, cls ) {
                        $('#class-select').append("<option value='"+ cls.id +"'>" + cls.grade.number + "-"+ cls.name +"</option>");
                    });
                });

                onChangeClassFiltering();
                updateSelectors();
            },
            error: function (data) {
                console.log('ERROR:' + data.data);
            }
        });
    });

    $('#school-select').change(function(){
        var schoolId = $(this).val();
        if(!$.isNumeric(schoolId)) {
            schoolId = 0;
        }
        console.log('school_select -> change at id : ' + schoolId);
        $.ajax({
            type: "GET",
            url: "{{route('classfiltering.get.classes')}}/" + schoolId,
            dataType: 'json',
            success: function( rst ) {
                var classes = rst.data;
                $('#class-select').find('option[value!="all"]').remove();
                $.each( classes, function( key, cls ) {
                    $('#class-select').append("<option value='"+ cls.id +"'>" +  cls.grade.number + "-"+ cls.name +"</option>");
                });

                onChangeClassFiltering();
                updateSelectors();
            },
            error: function (data) {
                console.log('ERROR:' + data.data);
            }
        });

    });

    $('#class-select').change(function(){
        onChangeClassFiltering();
        updateSelectors();
    });
{{--</script>--}}