<?php
return [
    'username' => [
        [
            'rule' => 'not_blank',
            'message' => 'Le nom d\'utilisateur ne peut pas être vide'
        ],

        [
            'rule' => 'no_space',
            'message' => 'Le nom d\'utilisateur ne peut pas comporter d\'espace'
        ],

        [
            'rule' => 'length',
            'max' => 255,
            'min' => 3,
            'message' => 'Le nom d\'utilisateur doit comporter entre 3 et 255 caractères'
        ]
    ],

    'firstName' => [
        [
            'rule' => 'not_blank',
            'message' => 'Le prénom ne peut pas être vide'
        ],

        [
            'rule' => 'length',
            'max' => 255,
            'min' => 3,
            'message' => 'Le prénom doit comporter entre 3 et 255 caractères'
        ]
    ],

    'email' => [
        [
            'rule' => 'email',
            'message' => 'L\'email n\'est pas valide'
        ]
    ]
];
