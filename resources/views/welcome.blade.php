@extends('layouts.layout')

@section('body')
  <section class="section is-centered columns is-fullheight is-vcentered">
    <div class="column is-5-desktop has-text-centered">
      <img class="rounded" src="{{ asset('img/favicon.png') }}">
      <div class="title">
        {{ config('app.name') }}
      </div>

      <form
        method="POST"
        action="{{ route('home.login') }}"
        class="container">
        @csrf
        <div class="field">
          <div class="control">
            <input
              class="input"
              :class="[ { 'is-danger': activeError } ]"
              @keypress="activeError = false"
              name="password"
              :autofocus="true"
              type="password"
              placeholder="{{ __('labels.password') }}">
          </div>
          @if ($errors->has('password'))
            <template v-once>@{{ (activeError = true) ? '' : '' }}</template>
            <p
              v-if="activeError"
              class="has-text-left help is-danger">
            {{ $errors->first('password') }}
            </p>
          @endif
        </div>
        <div class="field control">
          <button type="submit" class="button is-primary is-fullwidth">
            {{ __('labels.access') }}
          </button>
        </div>
      </form>
    </div>
  </section>
@endsection

@section('scripts')
  <script src="{{ mix('js/welcome.js') }}" charset="utf-8"></script>
@endsection
