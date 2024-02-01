<?php

return [
    "routes" => [
        'categories' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Category::class,
            'table' => 'categories',
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
            'table' => 'banks',
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:150|unique:banks,name',
                    'code' => 'required|string|max:10',
                    'country_id' => 'required|required|exists:countries,id',
                ],
                'update' => [
                    'bank_id' => 'required|exists:banks,id',
                    'name' => 'sometimes|required|string|max:150|unique:banks,name',
                    'code' => 'sometimes|required|string|max:10',
                    'country_id' => 'sometimes|required|exists:countries,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['country'],
        ],

      
      'bank_details' => [
            'model' => \Transave\ScolaBookstore\Http\Models\BankDetail::class,
            'table' => 'bank_details',
            'rules' => [
                'store' => [
                    'user_id' => 'required|exists:users,id',
                    'bank_id' => 'required|exists:banks,id',
                    'account_number' => 'required|integer',
                    'account_name' => 'required|string|max:225',
                ],
                'update' => [
                    'bank_detail_id' => 'required|exists:bank_details,id',
                    'user_id' => 'sometimes|required|exists:users,id',
                    'bank_id' => 'sometimes|required|exists:banks,id',
                    'account_number' => 'sometimes|required|integer',
                    'account_name' => 'sometimes|string|max:225',
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
            'table' => 'schools',
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
            'table' => 'saves',
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
            'table' => 'publishers',
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


        'countries' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Country::class,
            'table' => 'countries',
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:150|unique:countries,name',
                    'code' => 'sometimes|required|string|max:10',
                ],
                'update' => [
                    'name' => 'sometimes|required|string|max:150',
                    'code' => 'sometimes|required|string|max:10',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

        'states' => [
            'model' => \Transave\ScolaBookstore\Http\Models\State::class,
            'table' => 'states',
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:150|unique:states,name',
                    'capital' => 'sometimes|required|string|max:80',
                    'country_id' => 'sometimes|required|exists:countries,id',
                ],
                'update' => [
                    'name' => 'sometimes|required|string|max:150|unique:states,name',
                    'capital' => 'sometimes|required|string|max:80',
                    'country_id' => 'sometimes|required|exists:countries,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['country'],
        ],

        'lgs' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Lg::class,
            'table' => 'lgs',
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:150|unique:lgs,name',
                    'state_id' => 'sometimes|required|exists:states,id',
                ],
                'update' => [
                    'name' => 'sometimes|required|string|max:150|unique:lgs,name',
                    'state_id' => 'sometimes|required|exists:states,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['state'],
        ],
    ],

    "prefix" => "general",

    "middleware" => [],
];




