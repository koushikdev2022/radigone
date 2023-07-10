<?php
header("Content-Type:text/css");
$color1 = $_GET['color1']; // Change your Color Here
$color2 = $_GET['color2']; // Change your Color Here

function checkhexcolor($color1){
    return preg_match('/^#[a-f0-9]{6}$/i', $color1);
}

if (isset($_GET['color1']) AND $_GET['color1'] != '') {
    $color1 = "#" . $_GET['color1'];
}

if (!$color1 OR !checkhexcolor($color1)) {
    $color1 = "#336699";
}

function checkhexcolor2($color2){
    return preg_match('/^#[a-f0-9]{6}$/i', $color2);
}

if (isset($_GET['color2']) AND $_GET['color2'] != '') {
    $color2 = "#" . $_GET['color2'];
}

if (!$color2 OR !checkhexcolor2($color2)) {
    $color2 = "#336699";
}
?>

.bg--primary, .section--bg, .custom-table thead tr {
    background-color: <?= $color1 ?> !important;
}

.custom-table thead tr, .btn--primary, .badge--primary, .header-section.header-fixed, .footer-section, .page-container.show .sidebar-menu .sidebar-main-menu li .sidebar-submenu, .sidebar-single-menu.open .sidebar-submenu li.open a::after, .screenshot-edit .screenshot, .card-header, .submit-button {
  background-color: <?= $color1 ?> ;
}

.admin-reply-section{
  background-color: <?= $color1 ?>29;
}

@media only screen and (max-width: 991px) {
  .header-bottom-area .navbar-collapse {
    background-color: <?= $color1 ?>;
  }
}

@media (max-width: 991px) {
  .header-bottom-area .navbar-collapse .main-menu {
    background-color: <?= $color1 ?>;
  }
}

.scrollToTop, .preloader-area, .action-button, .previous_button:hover, .previous_button:focus, .file-upload-wrapper:before {
  background: <?= $color1 ?> ;
}

.text--primary, .ticket-button, .close-button {
    color: <?= $color1 ?>;
}

.border--primary {
  border: 1px solid <?= $color1 ?>;
}

.border-primary{
  border-color: <?= $color1 ?> !important;
}

.deposit-preview .deposit-list li {
  border: 1px dotted <?= $color1 ?>;
}

.border--primary {
    border: <?= $color1 ?>;
}

#msform #progressbar li.active::after, .bg--base, .submit-btn, .swiper-pagination .swiper-pagination-bullet-active, .btn--base::before, .btn--base::after, .btn--base.active:focus, .btn--base.active:hover, .badge--warning   {
  background-color: <?= $color2 ?> !important;
}

*::-webkit-scrollbar-button, *::-webkit-scrollbar-thumb, ::selection, .header-bottom-area .navbar-collapse .main-menu li .sub-menu li::before, .survey-list li .smile label:hover, .footer-social li:hover, #msform #progressbar li.active::before, .checkbox-item input[type="checkbox"]:checked ~ label::before, .bg--warning, .btn--warning, .faq-wrapper .faq-item.open .faq-title{
  background-color: <?= $color2 ?>;
}

.pagination .page-item.active .page-link, .pagination .page-item:hover .page-link, .footer-bottom-area::after, .radio-item input[type="radio"]:not(:checked) + label::after, .radio-item input[type="radio"]:checked + label::after, .action-button:hover, .action-button:focus, .previous_button, .text--warning {
  background: <?= $color2 ?>;
}

.text--base, .checkbox-wrapper .checkbox-item label a, .forgot-password a, .navbar-toggler span, .breadcrumb-item a, .breadcrumb-item.active::before, .odo-area .odo-title, .account-header .sub-title a, .contact-info-icon i {
  color: <?= $color2 ?>;
}

@media only screen and (max-width: 991px) {
  .custom-table tbody tr td::before {
    color: <?= $color2 ?>;
  }
}

.pagination .page-item.active .page-link, .pagination .page-item:hover .page-link, .checkbox-item input[type="checkbox"]:checked ~ label::before {
  border-color: <?= $color2 ?>;
}

.section-header .title-border, .section-header .title-border::before, .section-header .title-border::after, .btn--base, .border--warning {
  border: 1px solid <?= $color2 ?>;
}

.checkmark {
  box-shadow: inset 0px 0px 0px <?= $color2 ?>;
}

.checkmark__circle {
  stroke: <?= $color2 ?>;
}

@keyframes fill {
  100% {
    box-shadow: inset 0px 0px 0px 30px <?= $color2 ?>;
  }
}

.call-to-action-form {
  box-shadow: 5px 5px 0 0 <?= $color2 ?>;
}

.pagination .page-item.disabled span {
  background: <?= $color2 ?>49;
  border: 1px solid <?= $color2 ?>49;
}
