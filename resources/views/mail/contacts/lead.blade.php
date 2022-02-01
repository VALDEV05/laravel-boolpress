<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Contact Email</title>
</head>
<body>
    <div class="container">
        <h1>You have a new lead!</h1>
        <dl>
            <dd>Name: {{ $data['name'] }}</dd>
            <dd>Email: {{ $data['email'] }}</dd>
        </dl>
        <div class="message">
            <p class="mb-0">
                {{ $data['message'] }}
            </p>
        </div>
    </div>
</body>
</html>