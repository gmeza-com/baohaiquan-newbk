@component('components.block')
    @slot('title', trans('dashboard::language.welcome'))

    {{ trans('dashboard::language.welcome_message', ['time' => date('H:i d-m-Y')]) }}
@endcomponent