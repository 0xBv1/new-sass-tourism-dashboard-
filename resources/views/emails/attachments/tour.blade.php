
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $tour->title }}</title>
</head>
<body>
<h1 style="text-align: center"><a target="_blank" href="{{ $tour->link }}"> {{ $tour->title }} </a></h1>
<hr>
<br>
<br>
<br>
@if($tour->days->isNotEmpty())
    <h3>Tour Program</h3>
    @foreach($tour->days as $day)
        @if(!empty($day->title))
            <div>
                <h4>{{ $loop->iteration . '- ' . $day->title }}</h4>
                <p>{!! $day->description !!}</p>
            </div>
        @endif
    @endforeach
    <hr>
@endif

<p>Copyrights reserved to {{ config('app.name') }}</p>
</body>
</html>
