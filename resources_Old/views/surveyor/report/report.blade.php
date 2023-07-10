<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" type="image/x-icon">
    <title>{{ $general->sitename(__($page_title) ?? '') }}</title>

    <style>
       @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');

        * {
            box-sizing: border-box;
        }

        @page {
            max-width: 2480px;
            max-height: 3508px;
        }
        body {
            margin: 0;
            padding: 0;
            position: relative;
            font-family: "Josefin Sans";
            color: #333;
            line-height: 1.4;
        }

        .container-fluid {
            width: 100%;
            padding: 30px;
        }

        .container {
            max-width: 2480px;
            max-height: 3508px;
            margin: 0 auto;
            position: relative;
        }

        .container-inner {
            padding: 50px 50px;
            border: 1px solid #ebebeb;
        }

        .header-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #FF851B;
            padding-bottom: 20px;
            max-width: 2272px;
        }

        .logo {
            max-width: 150px;
        }

        .logo img {
            max-width: 100%;
        }

        .meta-data {
            max-width: 1140px;
            font-family: "kiwi maru";
            font-weight: 600;
            text-align: end;
        }

        .info-text {
            max-width: 2272px;
            padding: 40px 0;
            border-bottom: 1px solid #FF851B;
            padding: 20px;
            margin-bottom: 50px;
            text-align: center;
            font-weight: 600;
            color: #0a2b4d;
        }

        .info-text-two-area {
            margin-top: 50px;
        }
        .info-text-two-area label {
            font-weight: 600;
            margin-bottom: 15px;
            display: inline-block;
            font-size: 15px;
        }

        .info-text-two {
            margin: 0 auto;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            border: 1px solid #FF851B;
        }

        .question-wrapper {
            max-width: 2272px;
        }

        .question-area {
            max-width: 2272px;
        }

        .question-area h3 {
            text-align: center;
        }

        .footer {
            max-width: 2272px;
        }
        .footer p {
            text-align: center;
            padding: 10px 0;
            background: #FF851B;
            color: #fff;
            font-weight: 500;
        }

    .footer p a {
        text-decoration: none;
        color: #fff;

    }

    .question {
        max-width: 2272px;
    }


    .question-no {
            font-size: 18px;
            margin-bottom: 0;
            max-width: 2272px;
        }

    .options li {
        list-style: none;
        padding: 5px 0;
        font-weight: 500;
        max-width: 2432px;
    }

    .options li div {
        width: 50%;
        display: inline-block;
    }
    .options li div:nth-child(2) {
        text-align: right;
    }

    .options li span {
        color: #FF851B;
        font-weight: 700;
    }


    .options li .vote-info {
        margin-right: 15px;
        margin-left: 35px;
        color: #52278a;
        font-weight: 600;
        font-size: 13px;
        text-align: right;
    }
    .options li .vote-rate {
        color: #da0c0c;
        font-weight: 500;
        font-size: 14px;
    }

    .info-text-two-area {
        max-width: 100%;
    }
    .info-text-two-area label{
        max-width: 2272px;
    }

    .info-text-two {
        max-width: 2272px;
    }
    .abc {text-align: center;}
    .btn-download {
        display: inline-block;
        padding: 8px 12px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        outline: none;
        color: #fff;
        background-color: #FF851B;
        border: none;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 25px;
    }

    .btn-download:active {
        background-color: #FF851B;
        box-shadow: 0 5px #FF851B49;
        transform: translateY(4px);
    }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="abc">
            <a href="javascript:void(0)" class="btn-download">@lang('Download Report')</a>
        </div>
        <div class="container" id="block1">
            <div class="container-inner">
                <div class="header-area">
                    <div class="logo" >
                        <a href="#0">
                            <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
                        </a>
                    </div>
                    <div class="meta-data">
                        <span>@lang('Date'):</span>
                        <span>{{Carbon\Carbon::now()->format('Y-m-d')}}</span>

                    </div>
                </div>
                <div class="info-text">
                    {{__($survey->name)}}
                </div>
                <div class="question-wrapper">
                    <div class="question-area">
                        @foreach ($survey->questions as $item)
                            @if ($item->custom_input == 1)
                                <h3>[@lang('Custom Answers')]</h3>
                                @php
                                    $custom_answers = App\Answer::where('question_id',$item->id)->get(['custom_answer']);
                                @endphp
                                <div class="info-text-two-area">
                                    <label>{{__($item->custom_question)}}</label>
                                    <div class="info-text-two">
                                        @foreach($custom_answers as $c_ans) @if($c_ans->custom_answer) "{{$c_ans->custom_answer}}", @endif @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="question">
                                    <h5 class="question-no">{{$loop->index+1}}) {{__($item->question)}}</h5>
                                    <ul class="options">
                                        @foreach ($item->options as $data)
                                            @php
                                                if(App\Answer::where('question_id',$item->id)->whereJsonContains('answer',$data)->exists()) {
                                                    $answer = App\Answer::where('question_id',$item->id)->whereJsonContains('answer',$data)->count();
                                                    $total_answer = App\Answer::where('question_id',$item->id)->count();
                                                    $percent = getAmount((($answer / $total_answer) * 100),2);
                                                    //if($item->answers) {
                                                        //$percent = getAmount((($answer / $item->answers->count()) * 100),2);
                                                        //dd($item->answers->count());
                                                    //}else {
                                                        //$percent = 100;
                                                    //}
                                                }else {
                                                    $answer = 0;
                                                    $percent = 0;
                                                }

                                            @endphp

                                            <li><div><span>{{$loop->index+1}}.</span> {{__($data)}}</div><div> <span class="vote-info"> @lang('Total response') : {{$answer}} </span><span class="vote-rate">[ {{$percent}}% ]</div></span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="footer">
                    <p>{{__(@$footer_content->data_values->copyright)}}</p>
                </div>
            </div>
        </div>
    </div>


    <script src="{{asset($activeTemplateTrue.'js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/html2pdf.bundle.min.js')}}"></script>
    <script>
        "use strict";

        const options = {
            margin: 0.3,
            filename: '{{$filename}}',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        }

        var objstr = document.getElementById('block1').innerHTML;

        var strr = objstr;

        $('.btn-download').click(function(e){
            e.preventDefault();
            var element = document.getElementById('demo');
            html2pdf().from(strr).set(options).save();
        });
    </script>
</body>
</html>
