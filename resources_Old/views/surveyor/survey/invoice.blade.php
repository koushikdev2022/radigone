<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Survey Invoice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .invoice-box {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }
    </style>
</head>

<body>
    <div class="container-fluid invoice-box">
        <div class="text-center">
            <img src="{{imageBase64(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo" width="300px"><br>
            <p class="font-weight-bold" style="color: #067C1D; font-size: 40px">INVOICE</p>
            <p>Invoice Number: <b>{{$survey->id}}</b></p>
        </div>
        <div class="card my-2">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="py-1"><span class="font-weight-bold">Name</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->surveyor->firstname}} {{$survey->surveyor->lastname}}</span></td>
                        <td class="py-1"><span class="font-weight-bold">Address</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->surveyor->address->address}}, {{$survey->surveyor->address->state}}, {{$survey->surveyor->address->city}}, {{$survey->surveyor->address->country}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-bold">Mobile</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->surveyor->mobile}}</span></td>
                        <td class="py-1"><span class="font-weight-bold">{{$survey->surveyor->isfirm == 1 ? 'GSTIN No.' : 'PAN No.'}}</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->surveyor->isfirm == 1 ? $survey->surveyor->firm_gstin : $survey->surveyor->pan}}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="font-weight-normal">ORDER SUMMARY</div>
        <div class="card my-2">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td><span class="font-weight-bold">Amount Paid</span></td>
                        <td><span class="font-weight-bold float-right">Rs. {{getAmount($survey->totalprice)}}</span></td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td class="py-1"><span class="font-weight-normal">Name</span></td>--}}
{{--                        <td class="py-0"><span class="font-weight-normal float-right">{{$survey->surveyor->firstname}} {{$survey->surveyor->lastname}}</span></td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td class="py-1"><span class="font-weight-normal">Address</span></td>--}}
{{--                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->surveyor->address->address}}, {{$survey->surveyor->address->state}}, {{$survey->surveyor->address->city}}, {{$survey->surveyor->address->country}}</span></td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td class="py-1"><span class="font-weight-normal">Mobile</span></td>--}}
{{--                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->surveyor->mobile}}</span></td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td><span class="font-weight-bold">Description</span></td>--}}
{{--                        <td><span class="font-weight-normal float-right">{{$survey->p_specification}}</span></td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td><span class="font-weight-bold">Transaction ID</span></td>
                        <td><span class="font-weight-normal float-right">{{$survey->txn}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-bold">Product</span></td>
                        <td class="py-1"><span class="font-weight-bold float-right">Amount (Rs.)</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-bold">{{$survey->p_name}}</span></td>
                        <td class="py-1"><span class="font-weight-bold float-right">Rs. {{getAmount($survey->total_without_gst)}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-normal">Click Charge</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">Rs. {{getAmount($survey->category->price + $survey->adtype->price + $survey->slides_time)}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-normal">Pass Charge</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">Rs. {{getAmount($survey->adtype->price)}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-normal">Per Second Charge</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">Rs. {{getAmount($survey->slides_time)}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-normal">Category</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">{{ $survey->category->name }}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-normal">Category Price</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">Rs. {{getAmount($survey->category->price)}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-normal">Views</span></td>
                        <td class="py-1"><span class="font-weight-normal float-right">{{$survey->total_views}}</span></td>
                    </tr>
                    <tr>
                        <td class="py-1"><span class="font-weight-bold">GST ({{$gst}}%)</span></td>
                        <td class="py-1"><span class="font-weight-bold float-right">Rs. {{getAmount($survey->totalprice - $survey->total_without_gst)}}</span></td>
                    </tr>
                    <tr style="background-color: rgb(232, 229, 239);">
                        <td class="py-1"><span class="font-weight-bold">AMOUNT PAID</span></td>
                        <td class="py-1"><span class="font-weight-bold float-right">Rs. {{getAmount($survey->totalprice)}}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
