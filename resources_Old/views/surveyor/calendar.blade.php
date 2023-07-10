@extends('surveyor.layouts.app')
@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />

  <style>
     @import url("https://fonts.googleapis.com/css2?family=Quicksand&display=swap");

     * {
     margin: 0;
     padding: 0;
     box-sizing: border-box;
     font-family: "Quicksand", sans-serif;
     }

     html {
     font-size: 62.5%;
     }

     .container {
     width: 100%;
     height: 100vh;
     background-color: #153365;
     color: #eee;
     display: flex;
     justify-content: center;
     align-items: center;
     }

     .calendar {
     width: 47rem;
     height: 55rem;
     border-radius: 30px;
     -webkit-border-radius: 30px;
     -moz-border-radius: 30px;
     -ms-border-radius: 30px;
     -o-border-radius: 30px;
     background-color: #041837;
     box-shadow: 0 0.5rem 3rem rgba(0, 0, 0, 0.4);
     }

     .month {
     width: 100%;
     height: 12rem;
     background-color: #4a0ba6;
     display: flex;
     justify-content: space-between;
     align-items: center;
     padding: 0 2rem;
     text-align: center;
     text-shadow: 0 0.3rem 0.5rem rgba(0, 0, 0, 0.5);
     }

     .month i {
     font-size: 2.5rem;
     cursor: pointer;
     }

     .month h1 {
     font-size: 3rem;
     font-weight: 400;
     text-transform: uppercase;
     letter-spacing: 0.2rem;
     margin-bottom: 1rem;
     }

     .month p {
     font-size: 1.6rem;
     }

     .weekdays {
     width: 100%;
     height: 5rem;
     padding: 0 0.4rem;
     display: flex;
     align-items: center;
     }

     .weekdays div {
     font-size: 1.5rem;
     font-weight: 400;
     letter-spacing: 0.1rem;
     width: calc(44.4rem / 7);
     display: flex;
     justify-content: center;
     align-items: center;
     text-shadow: 0 0.3rem 0.5rem rgba(0, 0, 0, 0.5);
     }

     .days {
     width: 100%;
     display: flex;
     flex-wrap: wrap;
     padding: 0.2rem;
     }

     .days div {
     font-size: 1.4rem;
     margin: 0.3rem;
     width: calc(40.4rem / 7);
     height: 5.5rem;
     display: flex;
     justify-content: center;
     align-items: center;
     text-shadow: 0 0.3rem 0.5rem rgba(0, 0, 0, 0.5);
     transition: background-color 0.2s;
     -webkit-transition: background-color 0.2s;
     -moz-transition: background-color 0.2s;
     -ms-transition: background-color 0.2s;
     -o-transition: background-color 0.2s;
     }

     .days div:hover:not(.today) {
     border: 0.2rem solid #777;
     cursor: pointer;
     text-decoration: none;
     border-radius: 50%;
     -webkit-border-radius: 50%;
     -moz-border-radius: 50%;
     -ms-border-radius: 50%;
     -o-border-radius: 50%;
     }

     .prev-date,
     .next-date {
     opacity: 0.5;
     }

     .today {
     background-color: #4e0ba6;
     border-radius: 50%;
     -webkit-border-radius: 50%;
     -moz-border-radius: 50%;
     -ms-border-radius: 50%;
     -o-border-radius: 50%;
     }

  </style>
@endpush
@section('panel')
  <div class="container">
    <div class="calendar">
      <div class="month">
        <i class="fas fa-angle-left prev"></i>
        <div class="date">
          <h1></h1>
          <p></p>
        </div>
        <i class="fas fa-angle-right next"></i>
      </div>
      <div class="weekdays">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
      </div>
      <div class="days"></div>
    </div>
  </div>

@endsection

@push('script')

  <script>
     const date = new Date();

     const renderCalendar = () => {
     date.setDate(1);

     const monthDays = document.querySelector(".days");

     const lastDay = new Date(
     date.getFullYear(),
     date.getMonth() + 1,
     0
     ).getDate();

     const prevLastDay = new Date(
     date.getFullYear(),
     date.getMonth(),
     0
     ).getDate();

     const firstDayIndex = date.getDay();

     const lastDayIndex = new Date(
     date.getFullYear(),
     date.getMonth() + 1,
     0
     ).getDay();

     const nextDays = 7 - lastDayIndex - 1;

     const months = [
     "January",
     "February",
     "March",
     "April",
     "May",
     "June",
     "July",
     "August",
     "September",
     "October",
     "November",
     "December"
     ];

     document.querySelector(".date h1").innerHTML = months[date.getMonth()];

     document.querySelector(".date p").innerHTML = new Date().toDateString();

     let days = "";

     for (let x = firstDayIndex; x > 0; x--) {
     days += `<div class="prev-date">${prevLastDay - x + 1}</div>`;
     }

     for (let i = 1; i <= lastDay; i++) {
     if (
          i === new Date().getDate() &&
          date.getMonth() === new Date().getMonth()
     ) {
          days += `<div class="today">${i}</div>`;
     } else {
          days += `<div>${i}</div>`;
     }
     }

     for (let j = 1; j <= nextDays; j++) {
     days += `<div class="next-date">${j}</div>`;
     monthDays.innerHTML = days;
     }
     };

     document.querySelector(".prev").addEventListener("click", () => {
     date.setMonth(date.getMonth() - 1);
     renderCalendar();
     });

     document.querySelector(".next").addEventListener("click", () => {
     date.setMonth(date.getMonth() + 1);
     renderCalendar();
     });

     renderCalendar();

  </script>
  @endpush