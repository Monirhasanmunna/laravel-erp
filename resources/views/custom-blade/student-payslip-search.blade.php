@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-results__option{
            background: white;
        }
    </style>
@endpush
<input type="hidden" name="class_id" id="class_id">
<input type="hidden" name="shift_id" id="shift_id">
<div class="row py-2" id="all-row-py-2">
    <div class="col-sm-2"> <label for="session_id"> Academic Year</label>
        <select name="academic_year_id" id="session_id" class="form-control">
            <option value="">Select Session</option>
            @foreach ($academic_years as $academic_year)
                <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-2"> <label for="section_id">Section</label>
        <select name="section_id" id="section_id" class="form-control chosen-select">
            <option value="">Select Section</option>
        </select>
    </div>

    <div class="col-sm-2"> <label for="category_id">Select Category</label>
        <select name="category_id" id="category_id" class="form-control" required>
            <option value="">Select Category</option>
        </select>
    </div>

    <div class="col-sm-2"> <label for="group_id">Select Group</label>
        <select name="group_id" id="group_id" class="form-control" required>
            <option value="">Select Group</option>
        </select>
    </div>

    <div class="col-sm-2"> <label for="month">Select Month</label>
        <select name="month[]" id="month" class="form-control select2-multiple"  multiple="multiple" required>
            <option value="" disabled>Select Month</option>
            @foreach($months as $key => $month)
                <option value="{{$key}}">{{$month}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-2" id="process_btn">
        <button type="submit" id="btn-submit" class="btn btn-primary" style="margin-top: 35px"> <i class="fas fa-arrow-circle-right"></i>Proccess</button>
    </div>
</div>


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function (){
            $('.select2-multiple').select2();
        });
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
                    getsubjectByClass(data.class_id);
                });
        });

        //Get Category && Group
        function getCategoryGroup(class_id, section_id) {

            $.ajax({
                url: '/student/getCatSecGro/' + class_id + '/' + section_id,
                type: 'GET',
                success: function (data) {

                    let group = '<option hidden value="">Select Group</option>';
                    if (data['category']) {
                        let category = '<option hidden value="">Select Category</option>';
                        data.category.map(function (val, index) {
                            category += `<option  value='${val.id}'>${val.name}</option>`;
                        });
                        $('#category_id').html(category);
                    }

                    if (data['group']) {
                        let group = '<option hidden value="">Select Group</option>';
                        data.group.map(function (val, index) {
                            group += `<option  value='${val.id}'>${val.name}</option>`;
                        });
                        $('#group_id').html(group);
                    }
                }
            });
        }


        function getsubjectByClass(class_id) {
            $.ajax({
                type: 'GET',
                url: '/academic/number-sheet/subjectbyclassid/' + class_id,
                success: function (data) {
                    if ($("#subject")) {
                        data.subjects.map(function (val, index) {
                            var items = `<option value='${val.sub_name}'>${val.sub_name}</option>`;
                            $("#subject_id").append(items);
                        });
                    }
                }
            });
        }
    </script>
@endpush
