# Parsing People

- [The Api](#api)
    - [The URL](#url)
    - [The Headers](#headers)
    - [The Payload](#payload)
        - [Request](#request)
        - [Validation](#validation)
        - [Response 201](#response-201)
        - [Response 422](#response-422)

<a name="api"></a>
## The API

To begin, you should understand the API.  This will involve three parts: the url, the headers and the payload.

<a name="url"></a>
### The URL

The endpoint for parsing people is `<SITE_URL>/parse/people`.  This endpoint will only accept POST requests and will expect 
the request to match the headers and payload below.

<a name="headers"></a>
### The Headers

You will need to supply two headers to get proper results back.

```
Content-Type: application/json
Accept: application/json
```

> If you do not pass the accept header the application will treat this as a browser request instead of a JSON request.

<a name="payload"></a>
### The Payload

The main point of this endpoint is to parse the contents of your payload into usable data.  Your payload will be validated 
when sent and you will receive any of a number of errors if it is not valid.

<a name="request"></a>
#### Request

```
{
    "data": [
        {
            "first_name": "matt",
            "last_name": "stauffer",
            "age":31,
            "email":"matt@stauffer.com",
            "secret":"VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="
        },
        {
            "first_name":"dan",
            "last_name":"sheetz",
            "age": "99",
            "email": "dan@sheetz.com",
            "secret":"YWxidXF1ZXJxdWUuIHNub3JrZWwu"
        }
    ]
}
```

As you can see above, you must pass all of your people inside of a data object.  Each person must then adhere to the validation 
rules listed below.

<a name="validation"></a>
#### Validation

Property | Rules
-------- | -----
data | required
data.*.first_name | require, string
data.*.last_name | require, string
data.*.age | require, integer
data.*.email | require, valid email address
data.*.secret | require, string

<a name="response-201"></a>
#### Response 201

This is returned when your POST was a success and your data has been stored.  It will return to you the stored object.

```
{
    "id": 1,
    "emails": "dan@sheetz.com,matt@stauffer.com",
    "people": [
        {
            "first_name": "dan",
            "last_name": "sheetz",
            "name": "dan sheetz",
            "age": 99,
            "email": "dan@sheetz.com",
            "secret": "YWxidXF1ZXJxdWUuIHNub3JrZWwu"
        },
        {
            "first_name": "matt",
            "last_name": "stauffer",
            "name": "matt stauffer",
            "age": 31,
            "email": "matt@stauffer.com",
            "secret": "VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="
        }
    ],
    "created_at": "2017-03-17 17:26:50"
}
```

<a name="response-422"></a>
#### Response 422

You will receive this response if any of your person objects fails validation.  The possible results are included below.

```
{
    "data.1.first_name": [
        "The data.1.first_name field is required."
    ],
    "data.1.first_name": [
        "The data.1.first_name must be a string."
    ],
    "data.1.last_name": [
        "The data.1.last_name field is required."
    ],
    "data.1.last_name": [
        "The data.1.last_name must be a string."
    ],
    "data.1.age": [
        "The data.1.age field is required."
    ],
    "data.1.age": [
        "The data.1.age must be an integer."
    ],
    "data.1.age": [
        "The data.1.age must be at least 0."
    ],
    "data.1.email": [
        "The data.1.email field is required."
    ],
    "data.1.email": [
         "The data.1.email must be a valid email address."
    ],
    "data.1.secret": [
        "The data.1.secret field is required."
    ],
    "data.1.secret": [
        "The data.1.secret must be a string."
    ]
}
```
