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




