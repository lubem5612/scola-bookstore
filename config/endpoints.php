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

        'carts' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Cart::class,
            'rules' => [
                'store' => [
                    'user_id' => 'required|exists:users,id',
                    'book_id' => 'required|exists:books,id',
                    'quantity' => 'required|integer',
                    'amount' => 'required|integer',
                    'total_amount' => 'required|integer',
                ],
                'update' => [
                    'cart_id' => 'required|exists:carts,id',
                    'user_id' => 'sometimes|required|exists:users,id',
                    'book_id' => 'sometimes|required|exists:books,id',
                    'quantity' => 'sometimes|required|integer',
                    'amount' => 'sometimes|required|integer',
                    'total_amount' => 'sometimes|required|integer',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['user', 'book'],
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
                    'book_id' => 'required|exists:books,id',
                ],
                'update' => [
                    'save_id' => 'required|exists:saves,id',
                    'user_id' => 'sometimes|required|exists:users,id',
                    'book_id' => 'sometimes|required|exists:books,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['user', 'book'],
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




