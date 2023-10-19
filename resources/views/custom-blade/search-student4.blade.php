<input type="hidden" name="class_id" id="class_id">
<div class="row py-2" id="all-row-py-2">
    <div class="col-sm-2"> <label for="session_id"> Session</label>
        <select name="academic_year_id" id="session_id" class="form-control">
            <option value="">Select</option>
            @foreach ($academic_years as $academic_year)
                <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2"> <label for="class_id">Class</label>
        <select name="class_id" id="class_id" class="form-control class_id">
            <option value="">Select Class</option>
        </select>
    </div>
    <div class="col-sm-2"> <label for="exam_id">Select Exam</label>
        <select name="exam_id[]" id="exam_id" class="form-control exam_id select2-multiple" multiple="multiple">
            <option value="" disabled>Select Exam</option>
        </select>
    </div>
</div>

@push('js')
    <script>
        $('#session_id').change(function() {

            let session_id = $(this).val();

            $.get("{{ route('student.get-classes') }}", {
                    session_id
                },
                function(data) {
                    console.log(data);
                    let classHtml = '<option value="" >Select Section</option>';
                    let examHtml = '<option value="" disabled>Select Exam</option>';

                    $.each(data.classes, function(idx, val) {
                        classHtml += `<option value="${val.id}" >${val.name}</option>`;
                    });

                    $.each(data.exams, function(idx, val) {
                        examHtml += `<option value="${val.id}">${val.name}</option>`;
                    });

                    $('.class_id').html(classHtml);
                    $('.exam_id').html(examHtml);
                });
        });


    </script>
@endpush
