<?php

return [
    'show_custom_fields' => true,
    'custom_fields' => [
        'designation_occupation' => [
            'type' => 'text',
            'label' => 'Designation / Occupation',
            'placeholder' => 'Designation / Occupation',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
        'birthday' => [
            'type' => 'datetime',
            'label' => 'Birthday',
            'placeholder' => 'Birthday',
            'required' => true,
            'seconds' => false,
            'rules' => 'required',
        ],
        'biography' => [
            'type' => 'textarea',
            'label' => 'Biography',
            'placeholder' => 'Biography life interest',
            'required' => true,
            'rows' => '3',
        ],
    ]
];
