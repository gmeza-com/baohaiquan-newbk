@extends('theme::layout')

@section('content')
<form action="{{ url('/contact') }}" method="post" class="form-horizontal form-validate">
    <div class="form-group">
        <label for="name" class="col-md-4 label-control">{{ trans('contact::web.your_name') }}</label>
        <div class="col-md-8">
            <input type="text" name="name" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-md-4 label-control">{{ trans('contact::web.your_email') }}</label>
        <div class="col-md-8">
            <input type="email" name="email" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="subject" class="col-md-4 label-control">{{ trans('contact::web.your_subject') }}</label>
        <div class="col-md-8">
            <input type="text" name="subject" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label for="message" class="col-md-4 label-control">{{ trans('contact::web.your_name') }}</label>
        <div class="col-md-8">
            <textarea type="text" name="name" class="form-control" required></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-8 col-md-offset-4">
            <button type="submit" class="btn btn-primary">{{ trans('contact::web.send') }}</button>
        </div>
    </div>
</form>
@endsection