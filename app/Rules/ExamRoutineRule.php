<?php

namespace App\Rules;

use App\Models\ExamRoutine;
use Illuminate\Contracts\Validation\Rule;

class ExamRoutineRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // $routine = ExamRoutine::where('ins_class_id',$this->classId)->where('exam_id', $value)->first();
        // if(isset($routine)){
        //     return false;
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
