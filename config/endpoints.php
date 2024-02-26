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

        'resource-categories' => [
            'model' => \Transave\ScolaBookstore\Http\Models\ResourceCategory::class,
            'table' => 'resource-categories',
            'rules' => [
                'store' => [
                    'resource_id' => 'required|string|exists:resources,id',
                    'category_id' => 'required|string|exists:categories,id',
                ],
                'update' => [
                    'resource_id' => 'sometimes|required|string|exist:resources,id',
                    'category_id' => 'sometimes|required|string|exists:categories,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['resource', 'category'],
        ],

        'faculties' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Faculty::class,
            'table' => 'faculties',
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:100',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:100',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

        'departments' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Department::class,
            'table' => 'departments',
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:100',
                    'faculty_id' => 'required|string|exists:faculties,id',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:100',
                    'faculty_id' => 'sometimes|required|string|exists:faculties,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['faculty'],
        ],

        'carts' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Cart::class,
            'table' => 'carts',
            'rules' => [
                'store' => [
                    'user_id' => 'required|exists:users,id',
                    'resource_id' => 'required|exists:resources,id',
                    'quantity' => 'sometimes|required|integer',
                    'unit_price' => 'required|numeric|gt:0',
                    'is_selected' => 'sometimes|boolean|in:0,1'
                ],
                'update' => [
                    'quantity' => 'sometimes|required|integer',
                    'is_selected' => 'sometimes|boolean|in:0,1'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['user', 'resource'],
        ],

        'order-items' => [
            'model' => \Transave\ScolaBookstore\Http\Models\OrderItem::class,
            'table' => 'order-items',
            'rules' => [
                'store' => [
                    'order_id' => 'required|exists:orders,id',
                    'resource_id' => 'required|exists:resources,id',
                    'quantity' => 'sometimes|required|integer',
                    'unit_price' => 'required|numeric|gt:0',
                    'discount' => 'nullable|numeric|gt:0',
                    'discount_type' => 'required_if:discount,!=,null|in:amount,percent',
                ],
                'update' => [
                    'quantity' => 'sometimes|required|integer',
                    'unit_price' => 'sometimes|required|numeric|gt:0',
                    'discount' => 'nullable|numeric|gt:0',
                    'discount_type' => 'required_if:discount,!=,null|in:amount,percent',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['order', 'resource'],
        ],

        'pickups' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Pickup::class,
            'table' => 'pickups',
            'rules' => [
                'store' => [
                    'order_id' => 'required|exists:orders,id',
                    'address' => 'sometimes|required|string',
                    'country_id' => 'required|exists:countries,id',
                    'state_id' => 'sometimes|required|exists:states,id',
                    'lg_id' => 'sometimes|required|exists:lgs,id',
                    'recipient_name' => 'required|string',
                    'postal_code' => 'nullable',
                    'email' => 'required|email',
                    'alternative_phone' => 'nullable',
                ],
                'update' => [
                    'address' => 'sometimes|required|string',
                    'country_id' => 'sometimes|required|exists:countries,id',
                    'state_id' => 'sometimes|required|exists:states,id',
                    'lg_id' => 'sometimes|required|exists:lgs,id',
                    'recipient_name' => 'sometimes|required|string',
                    'postal_code' => 'nullable',
                    'email' => 'sometimes|required|email',
                    'alternative_phone' => 'nullable',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['order'],
        ],

        'notifications' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Notification::class,
            'table' => 'notifications',
            'rules' => [
                'store' => [
                    'sender_id' => 'required|exists:users,id',
                    'title' => 'nullable|string|max:300',
                    'message' => 'required|string|max:600',
                    'type' => 'nullable'
                ],
                'update' => [
                    'sender_id' => 'sometimes|required|exists:users,id',
                    'title' => 'nullable|string|max:300',
                    'message' => 'sometimes|required|string|max:600',
                    'type' => 'nullable'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['sender'],
        ],

        'notification-receivers' => [
            'model' => \Transave\ScolaBookstore\Http\Models\NotificationReceiver::class,
            'table' => 'notification-receivers',
            'rules' => [
                'store' => [
                    'receiver_id' => 'required|exists:users,id',
                    'notification_id' => 'required|exists:notifications,id',
                ],
                'update' => [
                    'receiver_id' => 'sometimes|required|exists:users,id',
                    'notification_id' => 'sometimes|required|exists:notifications,id',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['receiver', 'notification'],
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

        'reviews' => [
            'model' => \Transave\ScolaBookstore\Http\Models\Review::class,
            'table' => 'reviews',
            'rules' => [
                'store' => [
                    'user_id' => 'sometimes|required|exists:users,id',
                    'resource_id' => 'required|exists:resources,id',
                    'review' => 'nullable|string|max:750',
                    'rating' => 'required|integer|min:1|max:5',
                ],
                'update' => [
                    'resource_id' => 'sometimes|required|exists:resources,id',
                    'review' => 'nullable|string|max:750',
                    'rating' => 'nullable|integer|min:1|max:5',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['user', 'resource'],
        ],
    ],

    "prefix" => "general",

    "middleware" => [],
];




