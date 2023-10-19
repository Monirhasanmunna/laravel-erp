<input type="hidden" name="class_id" id="class_id">
<div class="row py-2" id="all-row-py-2">
    <div class="col-sm-2"> <label for="session_id"> Session</label>
        <select name="academic_year_id" id="session_id" class="form-control">
            <option value="">Select Session</option>
            @foreach ($academic_years as $academic_year)
                <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2"> <label for="section_id">Section</label>
        <select name="section_id" id="section_id" class="form-control section_id">
            <option value="">Select Section</option>
        </select>
    </div>
    <div class="col-sm-2" id="cat-div"> <label for="category_id">Select Category</label>
        <select name="category_id" id="category_id" class="form-control category_id">
            <option value="">Select Category</option>
        </select>
    </div>
    <div class="col-sm-2" id="group-div"> <label for="group_id">Select Group</label>
        <select name="group_id" id="group_id" class="form-control group_id">
            <option value="">Select Group</option>
        </select>
    </div>

    <div class="col-sm-2"> <label for="group_id">Select Exam</label>
        <select name="exam_id" id="exam_id" class="form-control exam_id">
            <option value="">Select Exam</option>
        </select>
    </div>
    <div class="col-sm-2">
        <button type="submit" id="search-btn" style="margin-top:36px" class="btn btn-primary"><i class="fas fa-arrow-circle-right"></i> Proccess</button>
    </div>
</div>

@push('js')
    <script>
        $('#session_id').change(function() {

            let session_id = $(this).val();

            $.get("{{ route('get-classes') }}", {
                    session_id
                },
                function(data) {
                    console.log(data);
                    let classHtml = '<option value="" >Select Section</option>';
                    let examHtml = '<option value="" >Select Exam</option>';

                    $.each(data.sections, function(idx, val) {
                        classHtml += `<option value="${val.id}" data-class="${val.class_id}">${val.class}-${val.shift}-${val.name}</option>`;
                    });

                    $.each(data.exams, function(idx, val) {
                        examHtml += `<option value="${val.id}">${val.name}</option>`;
                    });

                    $('.section_id').html(classHtml);
                    $('.exam_id').html(examHtml);
                });
        });

        $('.section_id').change(function() {

            let section_id = $(this).val();
            let class_id = $(this).find(':selected').data('class');
            $('#class_id').val(class_id);

            $.get("{{ route('get-categories-groups') }}", {
                    section_id
                },
                function(data) {

                    let categories = '<option value="">Select Category</option>';
                    let groups = '<option value="">Select Group</option>';

                    data.categories.map(function(item) {
                        categories += `<option value="${item.id}">${item.name}</option>`;
                    });

                    data.groups.map(function(item) {
                        groups += `<option value="${item.id}">${item.name}</option>`;
                    });

                    $('.category_id').html(categories);
                    $('.group_id').html(groups);
                });
        });
    </script>
@endpush
