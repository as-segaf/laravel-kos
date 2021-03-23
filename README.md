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
        "status" : "string"
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
            "status" : "string"
        },
        {
            "id" : "integer, unique",
            "name" : "string",
            "description" : "string",
            "length" : "integer",
            "width" : "integer",
            "status" : "string"
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
    "status" : "string"
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
        "status" : "string"
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
    "status" : "string"
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
        "status" : "string"
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
        "status" : "string"
    }
}
```