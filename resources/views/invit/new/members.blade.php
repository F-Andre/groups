@component('mail::message')
{{-- Greeting --}}
<p>Bonjour,</p>
{{-- Intro Lines --}}

<p>Vous recevez ce mail car {{ $name }} vous invite à rejoindre son groupe "{{ $groupName }}"</p>
<p>Pour cela vous devez avoir un compte sur le site <a href="https://groups.fabienandre.com">groups.fabienandre.com</a></p>
<p>Si vous avez déjà un compte, vous pouvez rejoindre le groupe en cliquant sur le bouton ci-dessous</p>

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

<small>Merci de ne pas répondre à cet email. Les messages reçus à cette adresse ne sont pas lus et ne reçoivent donc aucune réponse.</small>

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