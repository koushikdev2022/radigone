@php
	$about_content = getContent('about.content',true);
@endphp
<section class="about-section ptb-80">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-5 col-md-6 mb-30">
                <div class="about-thumb">
                    <img src="{{ getImage('assets/images/frontend/about/'. @$about_content->data_values->image,'700x700') }}" alt="@lang('about-image')">
                </div>
            </div>
            <div class="col-xl-6 offset-xl-1 col-md-6 mb-30">
                <div class="about-area">
                    <h2 class="section-title mb-20">{{__(@$about_content->data_values->heading)}}</h2>
                    <p>At Radigone we are facilitating the users on three platforms</p>
                    <ul class="mb-3">
                        <li>1. Viewers</li>
                        <li>2. Sponsors</li>
                        <li>3. Agents</li>
                    </ul>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Viewers</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Sponsors</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Agents</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                            <p class="mt-3">For Viewers</p>
                            <ul>
                                <li>1. Providing financial assistance to clear routine unavoidable expenses like Phone bill,Mobile bill, Internet bills, Petrol/FASTag/Electricity and water bills, Insurance policy etc</li>
                                <li>2. Offering encashment of Radigone points into bank account</li>
                                <li>3. Very sophisticated Radigone points reporting system</li>
                                <li>4. Freedom of watching advertisements as per viewer’s choice and area of interest</li>
                                <li>5. No Gender/Language/Place of living/Education background/Reference basis discrimination.</li>
                                <li>6. No travelling here and there so no Fuel/Time and fare expenses</li>
                                <li>7. Latest updates from your favourite company brands</li>
                                <li>8. Special discount for Radigone viewers from advertisement companies*</li>
                                <li>9. How to opt out from advertisement campaign shared by all companies to protect your privacy</li>
                                <li>10. Creating business opportunities for Viewers, Agents and Sponsors/Companies</li>
                            </ul>
                            <a href="/register" class="btn--base mt-2">Sign up Now</a>
                            <!--<p>{{__(@$about_content->data_values->description)}}</p>-->
                            <div class="about-btn mt-40">
                                <!--<a href="{{@$about_content->data_values->button_url}}" class="btn--base">{{__(@$about_content->data_values->button_name)}}</a>-->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            
                            <p>For Sponsors</p>
                            <ul>
                                <li>1. Helps in promotion and marketing of Goods/Services/Brands/Profession/Individual talent like Singing/Writing/Poetry/Painting/Motivational and Spiritual speakers etc</li>
                                <li>2. A unique marketing platform where sponsor/company will get database of customers who viewed and responded to their advertisement campaign which is not available in any form of available marketing media like Print media, Broadcast media (TV/Radio/Cinema/SMS/Whatsapp) and Internet media (social media/Vlogging/Blogging)</li>
                                <li>3. Providing unfiltered and filtered customer database to boost your trade/business and services</li>
                                <li>4. Very sophisticated Radigone customer database reporting system</li>
                                <li>5. Very responsive sales and support team</li>
                                <li>6. Attractive and very effective tools like Discount coupons/sale on basis of Radigone points/Viewer’s Birthday/ Viewer’s Anniversary/Legal abide and Post-paid arrangements available to boost your trade/business and services.</li>
                                <li>7. Time saver platform</li>
                                <li>8. Decrease routine expenses like Office/shop rent, Office/Shop water and electricity bill, Employees salary and other working expenses by prompt market response</li>
                                <li>9. User friendly platform to run advertisement campaign</li>
                                <li>10. Creating business opportunities for Sponsors/Companies, Viewers and Agents</li>
                            </ul>
                            <a href="/surveyor/register" class="btn--base mt-2">Sign up Now</a>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            
                            <p>For Agents</p>
                            <ul>
                                <li>1. Helps in generating income opportunity for agents</li>
                                <li>2. Providing a platform to showcase their talent and experience</li>
                                <li>3. No Gender/Language/Place of living/Education background/Reference basis discrimination.</li>
                                <li>4. No prior experience required</li>
                                <li>5. Helps in building entrepreneurship.</li>
                                <li>6. Recurring income in case sponsor publishing advertisement by using agents’ link</li>
                                <li>7. Win Win situation for Agents, Sponsors and Viewers</li>
                                <li>8. Help agent in generating customer database</li>
                                <li>9. Job/Work security.</li>
                                <li>10. Income security.</li>
                            </ul>
                            <a href="/agent/register" class="btn--base mt-2">Sign up Now</a>
                        </div>
                    </div>
{{--                    <p>At Radigone we are facilitating the users on three platforms</p>--}}
{{--                    <ul>--}}
{{--                        <li>1. Viewers</li>--}}
{{--                        <li>2. Sponsors</li>--}}
{{--                        <li>3. Agents</li>--}}
{{--                    </ul>--}}
{{--                    <p>For Viewers</p>--}}
{{--                     <ul>--}}
{{--                        <li>1. Providing financial assistance to clear routine unavoidable expenses like Phone bill,Mobile bill, Internet bills, Petrol/FASTag/Electricity and water bills, Insurance policy etc</li>--}}
{{--                        <li>2. Offering encashment of Radigone points into bank account</li>--}}
{{--                        <li>3. Very sophisticated Radigone points reporting system</li>--}}
{{--                        <li>4. Freedom of watching advertisements as per viewer’s choice and area of interest</li>--}}
{{--                        <li>5. No Gender/Language/Place of living/Education background/Reference basis discrimination.</li>--}}
{{--                        <li>6. No travelling here and there so no Fuel/Time and fare expenses</li>--}}
{{--                        <li>7. Latest updates from your favourite company brands</li>--}}
{{--                        <li>8. Special discount for Radigone viewers from advertisement companies*</li>--}}
{{--                        <li>9. How to opt out from advertisement campaign shared by all companies to protect your privacy</li>--}}
{{--                        <li>10. Creating business opportunities for Viewers, Agents and Sponsors/Companies</li>--}}
{{--                    </ul>--}}
{{--                    <!--<p>{{__(@$about_content->data_values->description)}}</p>-->--}}
{{--                    <div class="about-btn mt-40">--}}
{{--                        <!--<a href="{{@$about_content->data_values->button_url}}" class="btn--base">{{__(@$about_content->data_values->button_name)}}</a>-->--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-12 mb-2">--}}
{{--            <p>So what are you waiting for Sign up now</p>--}}
{{--            <p>For Sponsors</p>--}}
{{--            <ul>--}}
{{--                <li>1. Helps in promotion and marketing of Goods/Services/Brands/Profession/Individual talent like Singing/Writing/Poetry/Painting/Motivational and Spiritual speakers etc</li>--}}
{{--                <li>2. A unique marketing platform where sponsor/company will get database of customers who viewed and responded to their advertisement campaign which is not available in any form of available marketing media like Print media, Broadcast media (TV/Radio/Cinema/SMS/Whatsapp) and Internet media (social media/Vlogging/Blogging)</li>--}}
{{--                <li>3. Providing unfiltered and filtered customer database to boost your trade/business and services</li>--}}
{{--                <li>4. Very sophisticated Radigone customer database reporting system</li>--}}
{{--                <li>5. Very responsive sales and support team</li>--}}
{{--                <li>6. Attractive and very effective tools like Discount coupons/sale on basis of Radigone points/Viewer’s Birthday/ Viewer’s Anniversary/Legal abide and Post-paid arrangements available to boost your trade/business and services.</li>--}}
{{--                <li>7. Time saver platform</li>--}}
{{--                <li>8. Decrease routine expenses like Office/shop rent, Office/Shop water and electricity bill, Employees salary and other working expenses by prompt market response</li>--}}
{{--                <li>9. User friendly platform to run advertisement campaign</li>--}}
{{--                <li>10. Creating business opportunities for Sponsors/Companies, Viewers and Agents</li>--}}
{{--            </ul>--}}


{{--        </div>--}}
{{--        <div class="col-md-12">--}}
{{--            <p>So what are you waiting for Sign up now</p><br>--}}
{{--            <p>For Agents</p>--}}
{{--            <ul>--}}
{{--                <li>1. Helps in generating income opportunity for agents</li>--}}
{{--                <li>2. Providing a platform to showcase their talent and experience</li>--}}
{{--                <li>3. No Gender/Language/Place of living/Education background/Reference basis discrimination.</li>--}}
{{--                <li>4. No prior experience required</li>--}}
{{--                <li>5. Helps in building entrepreneurship.</li>--}}
{{--                <li>6. Recurring income in case sponsor publishing advertisement by using agents’ link</li>--}}
{{--                <li>7. Win Win situation for Agents, Sponsors and Viewers</li>--}}
{{--                <li>8. Help agent in generating customer database</li>--}}
{{--                <li>9. Job/Work security.</li>--}}
{{--                <li>10. Income security.</li>--}}
{{--            </ul>--}}
{{--            <p>So what are you waiting for Sign up now</p>--}}
{{--        </div>--}}
    </div>
</section>
