@component('components.block')
@slot('title', trans('dashboard::language.welcome'))

<div class="block-body">{{ trans('dashboard::language.welcome_message', ['time' => date('H:i d-m-Y')]) }}</div>
@endcomponent