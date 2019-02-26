<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>

    <div>
        Hi {{ $name }},
        <br>
        Please click on the link below or copy it into the address bar of your browser to reset your password:
        <br>

        <a href="{{ env('RESET_PASSWORD_URL') }}/{{ $token }}">Reset my password</a>

        <br/>
    </div>

</body>
</html>