<?php

return [
    "paths" => [
        "/names/{id}" => [
            "get" => [
                "description" => "Get name",
                "parameters" => [
                    [
                        "name" => "id",
                        "in" => "path",
                        "required" => true,
                        "schema" => ["type" => "integer"]
                    ]
                ],
                "responses" => [
                    200 => [
                        "description" => "OK",
                        "content" => [
                            "application/json" => [
                                "schema" => [
                                    "type" => "object",
                                    "required" => ['data', 'meta'],
                                    "properties" => [
                                        "data" => [
                                            "type" => "object",
                                            "required" => ['id', 'name', 'lang', 'country', 'type'],
                                            "properties" => [
                                                "id" => [
                                                    "type" => "integer"
                                                ],
                                                "name" => [
                                                    "type" => "string"
                                                ],
                                                "lang" => [
                                                    "type" => "string"
                                                ],
                                                "country" => [
                                                    "type" => "string"
                                                ],
                                                "type" => [
                                                    "type" => "string"
                                                ]
                                            ]
                                        ],
                                        "meta" => [
                                            "type" => "object",
                                            "required" => ['type'],
                                            "properties" => [
                                                "type" => [
                                                    "type" => "string"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

