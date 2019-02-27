# JWT Auth API

**Base URL:** `http://jwt-auth.test`

## Register 
**`POST`**  `/auth/register` post a request to create a new user and send a verification email.

| Parameter | Validation                                           |
|-----------|------------------------------------------------------|
| name      | `required` `string` `max:255`                        |
| email     | `required` `string` `email` `max:255` `unique:users` |
| password  | `required` `string` `min:8`                          |

### Example
```js
axios.post('/auth/register', {
    name: 'Joe Bloggs',
    email: 'email@domain.io',
    password: '12345678'
});
```

#### Success
```json
{
    "success": true,
    "data": {
        "user": {
            "name": "Joe Bloggs",
            "email": "email@domain.io",
            "updated_at": "2019-02-27 12:00:54",
            "created_at": "2019-02-27 12:00:54",
            "id": 3
        }
    },
    "message": "Check your email to complete your registration."
}
```

#### Error
```json
{
    "success": false,
    "error": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

## Verify 
**`POST`** `/auth/verify` verify the user registration with the verification token emailed to the user.

| Parameter | Validation                    |
|-----------|-------------------------------|
| token     | `required` `string` `size:30` |

### Example
```js
axios.post('/auth/verify', {
    token: 'fv7vvLOaL2vXhv5IJsuodf1NkwRD0M'
});
```

#### Success
```json
{
    "success": true,
    "message": "You have successfully verified your email address."
}
```

#### Error
```json
{
    "success": false,
    "error": {
        "token": [
            "The token field is required."
        ]
    }
}
```

## Login 
**`POST`** `/auth/login` log a user in and return the user object with token and expiration time.

| Parameter | Validation                            |
|-----------|---------------------------------------|
| email     | `required` `string` `email` `max:255` |
| password  | `required` `string` `min:8`           |

### Example
```js
axios.post('/auth/login', {
    email: 'email@domain.io',
    password: '12345678'
});
```

#### Success
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 5,
            "name": "Joe Bloggs",
            "email": "email@domain.io",
            "email_verified_at": "2019-02-27 12:20:50",
            "created_at": "2019-02-27 12:19:33",
            "updated_at": "2019-02-27 12:20:50",
            "is_verified": 1
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9qd3QtYXV0aC50ZXN0XC9hdXRoXC9sb2dpbiIsImlhdCI6MTU1MTI3MDY3OCwiZXhwIjoxNTUxMjc0Mjc4LCJuYmYiOjE1NTEyNzA2NzgsImp0aSI6IlZJU0xJNmlJdFN5cGJLMHciLCJzdWIiOjUsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.uZzoeuqwSpgcoaCSsoFftqH8MvTTi_ZpYrk6UWXmNtc",
        "expires": 3600
    }
}
```

#### Error
```json
{
    "success": false,
    "error": "We can't find your account or your password is incorrect, please try again."
}
```

## Logout 
**`GET`** `/auth/logout` log a user out by invalidating the token.

| Header        | Value            |
|---------------|------------------|
| Authorization | `Bearer [token]` |

### Example
```js
axios.get('/auth/logout', {
    headers: {
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9qd3QtYXV0aC50ZXN0XC9hdXRoXC9sb2dpbiIsImlhdCI6MTU1MTI3MDY3OCwiZXhwIjoxNTUxMjc0Mjc4LCJuYmYiOjE1NTEyNzA2NzgsImp0aSI6IlZJU0xJNmlJdFN5cGJLMHciLCJzdWIiOjUsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.uZzoeuqwSpgcoaCSsoFftqH8MvTTi_ZpYrk6UWXmNtc'
    }
});
```

#### Success
```json
{
    "success": true,
    "message": "You have successfully logged out."
}
```

#### Error
```json
{
    "success": false,
    "error": "TOKEN_INVALID"
}
```

## Recover 
**`POST`** `/auth/recover` recover a users' account by sending a recovery email with a reset token.

| Parameter | Validation                            |
|-----------|---------------------------------------|
| email     | `required` `string` `email` `max:255` |

### Example
```js
axios.post('/auth/recover', {
    email: 'email@domain.io'
});
```

#### Success
```json
{
    "success": true,
    "message": "Check your email for a reset link."
}
```

#### Error
```json
{
    "success": false,
    "error": {
        "email": [
            "User not found."
        ]
    }
}
```

## Reset 
**`POST`** `/auth/reset` reset a users' password with the recovery token emailed to the user.

| Parameter             | Validation                            |
|-----------------------|---------------------------------------|
| email                 | `required` `string` `email` `max:255` |
| password              | `required` `string` `min:8`           |
| password_confirmation | `required` `same:password`            |
| token                 | `required` `string`                   |

### Example
```js
axios.post('/auth/reset', {
    email: 'email@domain.io',
    password: 'new_password',
    password_confirmation: 'new_password',
    token: 'e0909ad52f6b00c43a954179ae36c7157cc60cd1e993aabc24b858836e76be3b'
});
```

#### Success
```json
{
    "success": true,
    "error": "Password updated."
}
```

#### Error
```json
{
    "success": false,
    "error": "Invalid token."
}
```

## Refresh 
**`POST`**  `/auth/refresh` generate a new auth token and return with the new token and expiration time.

| Header        | Value            |
|---------------|------------------|
| Authorization | `Bearer [token]` |

### Example
```js
axios.post('/auth/refresh', {
    headers: {
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9qd3QtYXV0aC50ZXN0XC9hdXRoXC9sb2dpbiIsImlhdCI6MTU1MTI3MDY3OCwiZXhwIjoxNTUxMjc0Mjc4LCJuYmYiOjE1NTEyNzA2NzgsImp0aSI6IlZJU0xJNmlJdFN5cGJLMHciLCJzdWIiOjUsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.uZzoeuqwSpgcoaCSsoFftqH8MvTTi_ZpYrk6UWXmNtc'
    }
});
```

#### Success
```json
{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9qd3QtYXV0aC50ZXN0XC9hdXRoXC9yZWZyZXNoIiwiaWF0IjoxNTUxMjY1OTc2LCJleHAiOjE1NTEyNjk1OTAsIm5iZiI6MTU1MTI2NTk5MCwianRpIjoiUlJza3J0N3ZCU05vT3ZMdSIsInN1YiI6MSwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.B0bewOeXOUTSOdYWSJLuaCOkWXnBGIdJFlCByI2woYw",
        "expires": 3600
    }
}
```

#### Error
```json
{
    "success": false,
    "error": "TOKEN_INVALID"
}
```
