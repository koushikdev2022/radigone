<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visitor Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    {{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />--}}
</head>
<body>
<div class="mt-5">
    <h2 class="text-center mb-3">Visitor Details</h2>
    <table class="table table-bordered mb-5">
        <thead>
        <tr class="table-danger">
            <th scope="col">Name</th>
            <th scope="col">Contact</th>
            <th scope="col">Email</th>
            @if($survey->ad_type == 1)
                <th scope="col">Age</th>
{{--                <th scope="col">Permanent Location</th>--}}
{{--                <th scope="col">Current Location</th>--}}
                <th scope="col">Gender</th>
                <th scope="col">Whatsapp</th>
                <th scope="col">Anniversary Date</th>
                <th scope="col">City</th>
            @elseif($survey->ad_type == 3)
                <th scope="col">Age</th>
                <th scope="col">Gender</th>
                <th scope="col">Martial Status</th>
                <th scope="col">Birthday</th>
                <th scope="col">Whatsapp</th>
                <th scope="col">Anniversary Date</th>
                <th scope="col">City</th>
            @elseif($survey->ad_type == 4)
                <th scope="col">Age</th>
                <th scope="col">Gender</th>
                <th scope="col">Martial Status</th>
                <th scope="col">Birthday</th>
                <th scope="col">Whatsapp</th>
                <th scope="col">Anniversary Date</th>
                <th scope="col">Profession</th>
                <th scope="col">Annual Income</th>
                <th scope="col">City</th>
                <th scope="col">Country</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($answers ?? '' as $data)
            <tr>
                <td>{{ $data->user->firstname }} {{ $data->user->lastname }}</td>
                <td>{{ substr($data->user->mobile, 2) }}</td>
                <td>{{ $data->user->email }}</td>
                @if($survey->ad_type == 1)
                    <td>{{ $data->user->age }}</td>
{{--                    <td>{{$data->user->address->address}}, {{$data->user->address->city}}, {{$data->user->address->state}}, {{$data->user->address->country}}</td>--}}
{{--                    <td>{{$data->user->address->address}}, {{$data->user->address->city}}, {{$data->user->address->state}}, {{$data->user->address->country}}</td>--}}
                    <td>{{ $data->user->gander }}</td>
                    <td>{{ $data->user->whatsaap }}</td>
                    <td>{{ $data->user->anniversary_date }}</td>
                    <td>{{ $data->user->address->city }}</td>
                @elseif($survey->ad_type == 3)
                    <td>{{ $data->user->age }}</td>
                    <td>{{ $data->user->gander }}</td>
                    <td>{{ $data->user->marital }}</td>
                    <td>{{ $data->user->dob }}</td>
                    <td>{{ $data->user->whatsaap }}</td>
                    <td>{{ $data->user->anniversary_date }}</td>
                    <td>{{ $data->user->address->city }}</td>
                @elseif($survey->ad_type == 4)
                    <td>{{ $data->user->age }}</td>
                    <td>{{ $data->user->gander }}</td>
                    <td>{{ $data->user->marital }}</td>
                    <td>{{ $data->user->dob }}</td>
                    <td>{{ $data->user->whatsaap }}</td>
                    <td>{{ $data->user->anniversary_date }}</td>
                    <td>{{ $data->user->occupation }}</td>
                    <td>{{ $data->user->annual_income }}</td>
                    <td>{{ $data->user->address->city }}</td>
                    <td>{{ $data->user->address->country }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{--<script src="{{ asset('js/app.js') }}" type="text/js"></script>--}}
</body>
</html>
