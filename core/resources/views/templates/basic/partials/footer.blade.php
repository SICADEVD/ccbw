@php
    $footer = getContent('footer.content', true);
    $contactInfo = getContent('contactInfo.content', true);
    $socialIcons = getContent('social_icon.element');
    $links = getContent('policy_pages.element', orderById: true);
@endphp


<footer class="footer-section bg--title-overlay bg_img bg_fixed"
    data-background="{{ getImage('assets/images/frontend/footer/' . $footer->data_values->background_image, '1920x1080') }}">
    <div class="footer-top pt-120 pb-120 position-relative">
        <div class="container">
            <div class="row gy-5 justify-content-between">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('logo')">
                            </a>
                        </div>
                        <p>
                            {{ __($footer->data_values->heading) }}
                        </p>
                        <ul class="social-icons justify-content-start">
                            @foreach ($socialIcons as $socialIcon)
                                <li>
                                    <a href="{{ $socialIcon->data_values->url }}" target="__blank">@php echo $socialIcon->data_values->social_icon @endphp</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h5 class="title text--white">@lang('Cooperative')</h5>
                        <ul class="useful-link">
                            <li>
                                <a href="{{ route('home') }}">@lang('Home')</a>
                            </li>
                            @foreach ($pages->take(4) as $data)
                                <li>
                                    <a href="{{ route('pages', [slug($data->slug)]) }}">
                                        {{ __($data->name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h5 class="title text--white">@lang('Useful Link')</h5>
                        <ul class="useful-link">
                            <li>
                                <a href="{{ route('order.tracking') }}">@lang('Order Tracking')</a>
                            </li>

                            @foreach ($links as $link)
                                <li><a href="{{ route('policy.pages', [slug($link->data_values->title), $link->id]) }}">
                                        {{ __($link->data_values->title) }}
                                    </a>
                                </li>
                            @endforeach


                            <li>
                                <a href="{{ route('contact') }}">@lang('Support')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h5 class="title text--white">@lang('Get In Touch')</h5>
                        <ul class="footer__widget-contact">
                            <li>
                                <i class="las la-map-marker"></i> {{ __($contactInfo->data_values->address) }}
                            </li>
                            <li>
                                <i class="las la-mobile"></i> @lang('Mobile'):
                                {{ $contactInfo->data_values->mobile }}
                            </li>
                            <li>
                                <i class="las la-fax"></i> @lang('Fax') : {{ $contactInfo->data_values->fax }}
                            </li>
                            <li>
                                <i class="las la-envelope"></i> {{ $contactInfo->data_values->email }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom position-relative text-center">
        <div class="container">
            &copy; @lang('Toutes les Right Reserved by') <a href="{{ route('home') }}">{{ __($general->site_name) }}</a>
        </div>
    </div>
</footer>

@php
$cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
@endphp
@if ($cookie->data_values->status == Status::YES && !\Cookie::get('gdpr_cookie'))
<div class="cookies-card text-center hide">
    <div class="cookies-card__icon bg--base">
        <i class="las la-cookie-bite"></i>
    </div>
    <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a
            href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
    <div class="cookies-card__btn mt-4">
        <button class="cmn--btn btn--lg w-100 policy">@lang('Allow')</button>
    </div>
</div>
@endif

