<input type="hidden" name="class_id" id="class_id">
<input type="hidden" name="shift_id" id="shift_id">
<div class="row py-2 " id="all-row-py-2">

    {{-- <div class="col-sm-2"> <label for="branch_id"> Branch</label>
        <select name="branch_id" id="branch_id" class="form-control">
            <option value="">Select branch</option>
            @foreach (@$branches as $branch)
            <option value="{{ @$branch->id }}">{{ @$branch->name }}</option>
            @endforeach
        </select>
    </div> --}}

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

    <div class="col-sm-2" id="process_btn">
        <button type="submit" id="btn-submit" class="btn btn-primary" style="margin-top: 31px"><i class="fas fa-arrow-circle-right"></i>Proccess</button>
    </div>
</div>



@push('js')
<script>
    $('#session_id').change(function () {
        let session_id = $(this).val();

        $.get("{{route('web.get-sections')}}",
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

                });
    });

    $('#section_id').change(function () {
        let section_id = $(this).val();
        $.get("{{route('web.get-class-shift')}}",
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
            url: '/web/getCatSecGro/' + class_id + '/' + section_id,
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

</script>
@endpush
