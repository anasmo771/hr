<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'required',
            'birth_date'  => 'required',
            'country'  => 'required',
            'city'  => 'required',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,pdf',
            'gender' => 'required|in:انثي,ذكر',
            'marital_status' => 'required|in:أرمل,مطلق,متزوج,أعزب',
            'start_date'  => 'required',
            'section_id'  => 'required',
            'type' => 'required|in:عقد,تعيين,إعارة,ندب',
            'section_id'       => ['required','exists:sub_sections,id'],
            'unit_staffing_id' => ['nullable','exists:unit_staffings,id'], 
            'N_id' => [
                'nullable',
                'numeric',
            ],
            'non_citizen_ref_no' => [
                'nullable',
            ],
        ];
    }

        /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'يجب إدخال الاسم',
            'start_date.required' => 'يجب ادخال تاريخ المباشرة',
            'section_id.required' => 'يجب تحديد الإدارة التابع لها',
            'N_id.numeric' => 'عذراً، يجب ان يمون الرقم الوطني متكون من ارقام.',
            'N_id.max' => 'يجب أن يكون الرقم الوطني 12 رقماً',
            'N_id.min' => 'يجب أن يكون الرقم الوطني 12 رقماً',
            'birth_date.required' => 'يجب إدخال تاريخ االميلاد',
            'country.required' => 'يجب إدخال البلد',
            'city.required' => 'يجب إدخال المدينة',
            'gender.required' => 'يجب إدخال الجنس',
            'gender.in' => 'يجب أن يكون نوع الجنس واحداً من الأنواع المحددة: ذكر، انثي',
            'marital_status.required' => 'يجب إدخال الحالة الاجتماعية',
            'marital_status.in' => 'يجب أن يكون نوع الحالة الوظيفية واحداً من الأنواع المحددة: أرمل ,مطلق ,متزوج ,أعزب ',
            'type.required' => 'يجب تحديد نوع التوظيف',
            'type.in' => 'يجب أن يكون نوع التوظيف واحداً من الأنواع المحددة: عقد, تعيين, إعارة, ندب',
        ];
    }
}
