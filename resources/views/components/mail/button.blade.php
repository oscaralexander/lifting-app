@props(['href'])

<a rel="noopener" target="_blank" href="{{ $href }}">
    <!--[if mso]>
    <i style="letter-spacing: 25px; mso-font-width: -100%; mso-text-raise: 30pt;">&nbsp;</i>
    <![endif]-->
    <span style="mso-text-raise: 15pt;">{{ $slot }}</span>
    <!--[if mso]>
    <i style="letter-spacing: 25px; mso-font-width: -100%;">&nbsp;</i>
    <![endif]-->
</a>