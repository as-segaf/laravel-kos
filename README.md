# API Spec

## Register

Request :
- Method : POST
- Endpoint : `/api/register`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json 
{
    "name" : "string",
    "email" : "string",
    "password" : "string"
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer,unique",
        "name" : "string",
        "email" : "string",
        "password" : "string",
    }
}
```

## Login

Request :
- Method : POST
- Endpoint : `/api/login`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json 
{
    "email" : "string",
    "password" : "string"
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer,unique",
        "name" : "string",
        "email" : "string",
        "password" : "string",
    },
    "token" : "string"
}
```


## Get Room

Request :
- Method : GET
- Endpoint : `/api/room/{room_id}`
- Header :
    - Accept: application/json

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "name" : "string",
        "description" : "string",
        "length" : "integer",
        "width" : "integer",
        "price_per_month" : "integer",
        "used_by" : "integer",
        "used_until" : "timestamp",
        "relations" : {
            "roomImage" : {
                "id" : "integer, unique",
                "room_id" : "integer",
                "img_name" : "string"
            }
        }
    }
}
```

## List Rooms

Request :
- Method : GET
- Endpoint : `/api/room`
- Header :
    - Accept: application/json

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : [
        {
            "id" : "integer, unique",
            "name" : "string",
            "description" : "string",
            "length" : "integer",
            "width" : "integer",
            "price_per_month" : "integer",
            "used_by" : "integer",
            "used_until" : "timestamp",
            "relations" : {
                "roomImage" : {
                    "id" : "integer, unique",
                    "room_id" : "integer",
                    "img_name" : "string"
                }
            }
        },
        {
            "id" : "integer, unique",
            "name" : "string",
            "description" : "string",
            "length" : "integer",
            "width" : "integer",
            "price_per_month" : "integer",
            "used_by" : "integer",
            "used_until" : "timestamp",
            "relations" : {
                "roomImage" : {
                    "id" : "integer, unique",
                    "room_id" : "integer",
                    "img_name" : "string"
                }
            }
        }
    ]
}
```


## Authentication

All API below must use this authentication

Request :
- Header :
    - Bearer Token : "your token"

## Create Room

Request :
- Method : POST
- Endpoint : `/api/room`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json 
{
    "name" : "string",
    "description" : "string",
    "length" : "integer",
    "width" : "integer",
    "price_per_month" : "integer",
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "name" : "string",
        "description" : "string",
        "length" : "integer",
        "width" : "integer",
        "price_per_month" : "integer",
        "used_by" : "integer",
        "used_until" : "timestamp",
        "relations" : {
            "roomImage" : {
                "id" : "integer, unique",
                "room_id" : "integer",
                "img_name" : "string"
            }
        }
    }
}
```

## Update Room

Request :
- Method : PATCH
- Endpoint : `/api/room/{room_id}`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json 
{
    "name" : "string",
    "description" : "string",
    "length" : "integer",
    "width" : "integer",
    "price_per_month" : "integer",
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "name" : "string",
        "description" : "string",
        "length" : "integer",
        "width" : "integer",
        "price_per_month" : "integer",
        "used_by" : "integer",
        "used_until" : "timestamp",
        "relations" : {
            "roomImage" : {
                "id" : "integer, unique",
                "room_id" : "integer",
                "img_name" : "string"
            }
        }
    }
}
```

## Delete Room

Requets : 
- Method : DELETE
- Endpoint : `/api/room/{room_id}` 
- Header :
    - Content-Type : application/json
    - Accept : application/json

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "name" : "string",
        "description" : "string",
        "length" : "integer",
        "width" : "integer",
        "price_per_month" : "integer",
    }
}
```

## List Orders

Request :
- Method : GET
- Endpoint : `/api/order`
- Header :
    - Content-Type: application/json
    - Accept: application/json

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "user_id" : "integer",
        "room_id" : "integer",
        "duration_in_month" : "integer",
        "status" : "string",
        "time_paid" : "timestamp"
    }
}
```

## Create Order

Request :
- Method : POST
- Endpoint : `/api/order`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json
{
    "room_id" : "integer",
    "duration_in_month" : "integer"
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "user_id" : "integer",
        "room_id" : "integer",
        "duration_in_month" : "integer",
        "status" : "string",
        "time_paid" : "timestamp"
    }
}
```

## Get Order

Request :
- Method : GET
- Endpoint : `/api/order/{order_id}`
- Header :
    - Content-Type: application/json
    - Accept: application/json

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "user_id" : "integer",
        "room_id" : "integer",
        "duration_in_month" : "integer",
        "status" : "string",
        "time_paid" : "timestamp"
    }
}
```

## Update Order

Request :
- Method : PATCH
- Endpoint : `/api/order`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json
{
    "status" : "string",
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "user_id" : "integer",
        "room_id" : "integer",
        "duration_in_month" : "integer",
        "status" : "string",
        "time_paid" : "timestamp"
    }
}
```

## Create Room Image

Request :
- Method : POST
- Endpoint : `/api/roomImage/{room_id}`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json
{
    "room_id" : "integer",
    "img_name" : "image"
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "room_id" : "integer",
        "img_name" : "string",
    }
}
```

## Update Room Image

Request :
- Method : PATCH
- Endpoint : `/api/roomImage/{roomImage_id}`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json
{
    "oldFile" : "string",
    "img_name" : "image"
}
```

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "room_id" : "integer",
        "img_name" : "string",
    }
}
```

## Delete Room Image

Request :
- Method : DELETE
- Endpoint : `/api/roomImage/{roomImage_id}`
- Header :
    - Content-Type: application/json
    - Accept: application/json

Response :

```json 
{
    "code" : "integer",
    "message" : "string",
    "data" : {
        "id" : "integer, unique",
        "room_id" : "integer",
        "img_name" : "string",
    }
}
```