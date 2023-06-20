<!DOCTYPE html>
<html>
<head>
    <title>Sharable Link</title>
    <meta property="og:title" content="{{ $data->Title }}">
    <meta property="og:description" content="{{ $data->Description }}">
    @if (count($data->images) <= 0)
    <meta property="og:image" content="{{ $data->image }}">
    @else
    <img src = "{{asset('storage/post/'.$data->SellerID.'/size500/'.$data->images[0]->FileName)}}">
    @endif
    
</head>
<body>
    <h1>{{ $data->Title }}</h1>
    <p>{{ $data->Description }}</p>
    @if (count($data->images) <= 0)
    <img class = "img-responsive" width = "100%" src = "{{asset('images/no-photo.jpg')}}">
    @else
    <img src = "{{asset('storage/post/'.$data->SellerID.'/size500/'.$data->images[0]->FileName)}}">
    @endif
</body>
</html>