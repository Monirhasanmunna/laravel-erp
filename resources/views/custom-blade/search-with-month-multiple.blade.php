<div class="row py-2" id="all-row-py-2">
    <div class="col-sm-2">
        <select name="session_id" id="session_id" class="form-control" required>
            <option value="">Select Session</option>
            @foreach ($sessions as $academic_year)
                <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2">
        <select name="section_id" id="section_id" class="form-control chosen-select" required>
            <option value="">Select Section</option>
        </select>
    </div>
    <div class="col-sm-2">
        <select name="category_id" id="category_id" class="form-control" required>
            <option value="">Select Category</option>

        </select>
    </div>
    <div class="col-sm-2">
        <select name="group_id" id="group_id" class="form-control" required>
            <option value="">Select Group</option>
        </select>
    </div>
    <div class="col-sm-2">
        <select name="month[]"  class="form-control month select2-multiple" multiple="multiple">
            <option value="">Select Month</option>
            @foreach ($months as $key => $month)
                <option value="{{$key}}">{{$month}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary" style="margin-top: 3px"> <i class="fa fa-arrow-circle-right"></i> Process</button>
    </div>
</div>
@push('js')
    <script>
        
        $('#session_id').change(function () {
            let session_id = $(this).val();
            $('.chosen-select').chosen("destroy");

            $.get("{{route('student.get-sections')}}",
                {
                    session_id
                },
                function (data) {
                    let html = '<option value="" selected hidden>Select Section</option>';

                    if (data) {
                        $.each(data.sections, function (i, item) {
                            html += `<option value="${item.id}">${item.class}-${item.shift}-${item.name}</option>`;
                        });
                    }

                    $('#section_id').html(html);
                    $('.chosen-select').chosen();


                });
        });

        $('#section_id').change(function () {
            let section_id = $(this).val();
            $.get("{{route('student.get-class-shift')}}",
                {
                    section_id
                },
                function (data) {
                    $('#class_id').val(data.class_id);
                    $('#shift_id').val(data.shift_id);
                    getCategoryGroup(data.class_id, section_id);
                });
        });

        //Get Category && Group
        function getCategoryGroup(class_id, section_id) {

            $.ajax({
                url: '/student/getCatSecGro/' + class_id + '/' + section_id,
                type: 'GET',
                success: function (data) {

                    let group = '<option value="">Select Group</option>';
                    let category = '<option value="">Select Category</option>';

                    data.category.map(function (val) {
                        category += `<option value='${val.id}'>${val.name}</option>`;
                    });
                    $('#category_id').html(category);

                    data.group.map(function (val) {
                        group += `<option  value='${val.id}'>${val.name}</option>`;
                    });
                    $('#group_id').html(group);

                }
            });
        }


    </script>
@endpush
