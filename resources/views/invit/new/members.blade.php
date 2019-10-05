@component('mail::message')
{{-- Greeting --}}
<p>Bonjour,</p>
{{-- Intro Lines --}}

<p>Vous recevez ce mail car {{ $name }} vous invite à rejoindre son groupe "{{ $groupName }}"</p>
<p>Pour cela vous devez avoir un compte sur le site <a href="https://groups.fabienandre.com">groups.fabienandre.com</a></p>
<p>Si vous n'en avez pas, vous pouvez en créer un en cliquant sur le bouton ci-dessous</p>

{{-- Action Button --}}
@component('mail::button', ['url' => $actionUrl])
Groups
@endcomponent

{{-- Outro Lines --}}
<p>Vous pouvez résiliez votre compte à tout moment.<br>Nous garantissons la confidentialité de vos données.</p>

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
"If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser: [:actionURL](:actionURL)',
[
'actionText' => $actionText,
'actionURL' => $actionUrl,
]
)
@endslot
@endisset
@endcomponent