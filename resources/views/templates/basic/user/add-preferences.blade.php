@extends($activeTemplate.'layouts.master')
@section('content')
<style>
INPUT[type=checkbox]:focus
{
    outline: 1px solid rgba(0, 0, 0, 0.2);
}

INPUT[type=checkbox]
{
    background-color: #DDD;
    border-radius: 2px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 17px;
    height: 17px;
    cursor: pointer;
    position: relative;
    top: 5px;
}

INPUT[type=checkbox]:checked
{
    background-color: #409fd6;
    background: #409fd6 url("data:image/gif;base64,R0lGODlhCwAKAIABAP////3cnSH5BAEKAAEALAAAAAALAAoAAAIUjH+AC73WHIsw0UCjglraO20PNhYAOw==") 3px 3px no-repeat;
}
.ulli{
columns:2;
padding:15px;
}
</style>
<div class="dashboard-area mt-30">
    <form action="{{route('user.add-prefernce-post')}}" method="post" enctype="multipart/form-data">
         @csrf
           <ul class="ulli">

                @foreach($categories as $categor)
                   <li><label><input type="checkbox" name="preferences[]" value="{{$categor->id}} " {{isPreferance($categor->name, auth()->id()) ? 'checked' : ''}}>{{$categor->name}}</label></li>
                @endforeach


           </ul>

       <input type="submit" name="Submit" value="Submit">
     </form>
</div>


@endsection
