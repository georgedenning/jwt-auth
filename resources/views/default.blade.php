<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>JWT Auth API</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                font-weight: 100;
            }

            table {
                width: 100%;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1 class="title">JWT Auth API</h1>
                <p>Base URL: {{ getenv('APP_URL') }}</p>

                <h2>Authentication</h2>
                <table>
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>Register</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>Registers a new user</td>
                        </tr>
                        <tr>
                            <td>URL</td>
                            <td>/auth/register</td>
                        </tr>
                        <tr>
                            <td>Method</td>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <td>Query</td>
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td>Example Name</td>
                                            <td>Required</td>
                                        </tr>
                                        <tr>
                                            <td>email</td>
                                            <td>example@domain.com</td>
                                            <td>Required</td>
                                        </tr>
                                        <tr>
                                            <td>password</td>
                                            <td>examplepassword</td>
                                            <td>Required</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Success Response</td>
                            <td>
                                <code>
                                    {
                                        "success": true,
                                        "data": {
                                            "user": {
                                                "name": "Example Name",
                                                "email": "example@domain.com",
                                                "updated_at": "2019-02-26 16:23:19",
                                                "created_at": "2019-02-26 16:23:19",
                                                "id": 13
                                            }
                                        },
                                        "message": "Check your email to complete your registration."
                                    }
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td>Error Response</td>
                            <td>
                                <code>
                                    {
                                        "success": false,
                                        "error": {
                                            "name": [
                                                "The name field is required."
                                            ],
                                            "email": [
                                                "The email must be a valid email address."
                                            ],
                                            "password": [
                                                "The password must be at least 8 characters."
                                            ]
                                        }
                                    }
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td>Sample</td>
                            <td>
                                <code>
                                    
                                </code>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
