<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>
    <body class="antialiased">
    <form enctype="multipart/form-data" method="POST" action="/api/v1/users/1/avatar">
        <input name="file" type="file" />
        <input type="submit" value="submit" />
    </form>
    </body>
</html>
