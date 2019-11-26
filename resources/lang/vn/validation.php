<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ' :attribute phải được chấp nhận.',
    'active_url' => ' :attribute không phải là một đường dẫn hợp lệ.',
    'after' => ' :attribute phải sau ngày :date.',
    'after_or_equal' => 'The :attribute phải  sau hoặc là ngày  :date.',
    'alpha' => ' :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash' => ' :attribute chỉ có thể là chữ cái, chữ số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => ' :attribute chỉ có thể là chữ cái và chữ số.',
    'array' => ' :attribute phải là một mảng.',
    'before' => ' :attribute phải trước ngày :date.',
    'before_or_equal' => ' :attribute phải trước hoặc là ngày :date.',
    'between' => [
        'numeric' => ' :attribute phải trong khoảng :min và :max.',
        'file' => ' :attribute phải trong khoảng :min và :max kilobytes.',
        'string' => ' :attribute phải trong khoảng :min đến :max kí tự.',
        'array' => ' :attribute phải trong khoảng :min và :max items.',
    ],
    'boolean' => ' :attribute trường này phải đúng hoặc sai.',
    'confirmed' => ':attribute xác nhận này không phù hợp.',
    'date' => ' :attribute không phải ngày hợp lệ.',
    'date_equals' => ' :attribute phải là ngày :date.',
    'date_format' => ' :attribute không phù hợp với định dạng :format.',
    'different' => ' :attribute và :other phải khác nhau.',
    'digits' => ' :attribute phải là :digits chữ số.',
    'digits_between' => ' :attribute phải từ :min đến :max chữ số.',
    'dimensions' => ' :attribute ảnh có kích thước không phù hợp.',
    'distinct' => ' :attribute có giá trị trùng lặp.',
    'email' => ' :attribute phải là địa chỉ có một email phù hợp.',
    'ends_with' => ' :attribute phải kết thúc bằng một trong các giá trị sau đây: :values',
    'exists' => 'Phần được chọn :attribute không phù hợp.',
    'file' => ' :attribute phải là một file.',
    'filled' => ' :attribute trường này bắt buộc phải có giá trị.',
    'gt' => [
        'numeric' => ' :attribute phải lớn hơn :value.',
        'file' => ' :attribute phải lớn hơn :value kilobytes.',
        'string' => ' :attribute phải lớn hơn :value kí tự.',
        'array' => ' :attribute phải có nhiều hơn :value items.',
    ],
    'gte' => [
        'numeric' => ' :attribute phải lớn hơn hoặc bằng :value.',
        'file' => ' :attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'string' => ' :attribute phải nhiều hơn hoặc bằng :value kí tự.',
        'array' => ' :attribute phải có :value items hoặc nhiều hơn.',
    ],
    'image' => ' :attribute phải có một hình ảnh.',
    'in' => 'Phần được chọn :attribute không phù hợp.',
    'in_array' => ' :attribute lĩnh vực không tồn tại trong :other.',
    'integer' => ' :attribute phải là số nguyên.',
    'ip' => ' :attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4' => ' :attribute phải là một địa chỉ IPv4.',
    'ipv6' => ' :attribute phải là một địa chỉ IPv6.',
    'json' => ' :attribute phải là một chuỗi JSON.',
    'lt' => [
        'numeric' => ' :attribute phải nhỏ hơn :value.',
        'file' => ' :attribute phải nhỏ hơn :value kilobytes.',
        'string' => ' :attribute phải có ít hơn hơn :value kí tự.',
        'array' => ' :attribute phải ít hơn :value items.',
    ],
    'lte' => [
        'numeric' => ' :attribute phải nhỏ hơn hoặc bằng :value.',
        'file' => ' :attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => ' :attribute phải ít hơn hoặc bằng :value kí tự.',
        'array' => ' :attribute phải ít hơn hoặc bằng :value items.',
    ],
    'max' => [
        'numeric' => ' :attribute không thể lớn hơn :max.',
        'file' => ' :attribute không thể lớn hơn :max kilobytes.',
        'string' => ' :attribute không thể nhiều hơn :max kí tự.',
        'array' => ' :attribute không thể nhiều hơn :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute phải có ít nhất :min.',
        'file' => ' :attribute phải tối thiều :min kilobytes.',
        'string' => ' :attribute phải tối thiểu :min kí tự.',
        'array' => ' :attribute phải có ít nhấtt :min items.',
    ],
    'not_in' => 'Phần được chọn :attribute có định dạng không hợp lệ.',
    'not_regex' => ' :attribute định dạng không hợp lệ .',
    'numeric' => ' :attribute phải là một số.',
    'present' => 'The :attribute field must be present.',
    'regex' => ' :attribute định dạng không hợp lệ.',
    'required' => ' :attribute trường được yêu cầu.',
    'required_if' => ' :attribute trường này được yêu cầu khi :other là :value.',
    'required_unless' => ' :attribute trường này được yêu cầu khi :other trong :values.',
    'required_with' => ' :attribute trường này được yêu cầu khi giá trị :values is present.',
    'required_with_all' => ' :attribute trường này được yêu cầu khi giá trị :values are present.',
    'required_without' => ' :attribute trường này được yêu cầu khi giá trị :values is not present.',
    'required_without_all' => ' :attribute trường này được yêu cầu khi không có giá trị :values .',
    'same' => ' :attribute và :other phải phù hợp.',
    'size' => [
        'numeric' => ' :attribute phải là :size.',
        'file' => ' :attribute phải là :size kilobytes.',
        'string' => ' :attribute phải là :size kí tự.',
        'array' => ' :attribute phải chứa :size items.',
    ],
    'starts_with' => ' :attribute phải bắt đầu bằng một trong nhừng giá trị sau: :values',
    'string' => ' :attribute phải là 1 chuỗi.',
    'timezone' => ' :attribute phải là một vùng hợp lệ.',
    'unique' => ' :attribute đã được thực hiện.',
    'uploaded' => ' :attribute không tải lên được.',
    'url' => ' :attribute định dang không hợp lệ.',
    'uuid' => ' :attribute phải là UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
