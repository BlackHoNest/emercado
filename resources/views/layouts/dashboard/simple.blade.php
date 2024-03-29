@props(['dir'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$dir ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://kit.fontawesome.com/fcdde7325c.js" crossorigin="anonymous"></script>
    

    <title>{{env('APP_NAME')}}</title>
    @include('partials.dashboard._head')
</head>
<body class data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0" >
    @include('partials.dashboard._body7')
</body>
</html>
@include('modals.register')
@include('modals.login')
@include('modals.share')