<?php

return [
    "routes" => [
        'categories' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Category::class,
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:60|unique:categories,name',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:60',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],


        'banks' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Bank::class,
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:225',
                    'code' => 'required|string|max:225',
                    'country' => 'required|string|max:225',
                ],
                'update' => [
                    'bank_id' => 'required|exists:banks,id',
                    'name' => 'sometimes|required|string|max:225',
                    'code' => 'sometimes|required|string|max:225',
                    'country' => 'sometimes|required|string|max:225',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

      
      'bank_details' => [
            'model' => \Transave\ScolaBookstore\Http\Models\BankDetail::class,
            'rules' => [
                'store' => [
                    'user_id' => 'required|exists:users,id',
                    'bank_id' => 'required|exists:banks,id',
                    'account_number' => 'required|integer|max:225',
                    'account_name' => 'required|string|max:225',
                ],
                'update' => [
                    'bank_detail_id' => 'required|exists:bank_details,id',
                    'user_id' => 'sometimes|required|exists:users,id',
                    'bank_id' => 'sometimes|required|exists:banks,id',
                    'account_number' => 'sometimes|required|integer|max:225',
                    'account_name' => 'sometimes|required|string|max:225',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['user', 'bank'],
        ],


        'schools' => [
            'model' => \Transave\ScolaBookstore\Http\Models\School::class,
            'rules' => [
                'store' => [
                    'faculty' => 'required|string',
                    'department' => 'required|string',
                ],
                'update' => [
                    'school_id' => 'required|exists:schools,id',
                    'faculty' => 'sometimes|required|string',
                    'department' => 'sometimes|required|string',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

        'saves' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Save::class,
            'rules' => [
                'store' => [
                    'user_id' => 'required|exists:users,id',
                    'resource_id' => 'required',
                    'resource_type' => 'sometimes|string|max:60',
                ],
                'update' => [
                    'save_id' => 'required|exists:saves,id',
                    'user_id' => 'sometimes|required|exists:users,id',
                    'resource_id' => 'sometimes|required',
                    'resource_type' => 'sometimes|string|max:60',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['user', 'book', 'report', 'journal', 'festchrisft', 'conference_paper', 'research_resource', 'monograph', 'article'],
        ],

        'publishers' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Publisher::class,
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:60|unique:publishers,name',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:60',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],
    ],

    "prefix" => "general",

    "middleware" => [],
];




