@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        @if(request()->id)
                            <form method="POST" action="{{ route('edit-stored-resource', ['id' => request()->id])}}">
                                @method('PATCH')
                                @else
                                    <form method="POST" action="{{route('store-stored-resources') }}">
                                        @endif
                                        @csrf

                                        <div class="form-group row">
                                            <label for="name"
                                                   class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                            <div class="col-md-6">
                                                <input id="name" type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       name="name"
                                                       value="{{  $storedResource['name'] ??  old('name') }}" required
                                                       autocomplete="name" autofocus>

                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="protocol"
                                                   class="col-md-4 col-form-label text-md-right">{{ __('Protocol') }}</label>

                                            <div class="col-md-6">
                                                <input id="protocol" type="text"
                                                       class="form-control @error('protocol') is-invalid @enderror"
                                                       name="protocol"
                                                       value="{{ $storedResource['protocol'] ?? old('protocol') }}"
                                                       required autocomplete="protocol" autofocus>

                                                @error('protocol')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="domain"
                                                   class="col-md-4 col-form-label text-md-right">{{ __('domain') }}</label>

                                            <div class="col-md-6">
                                                <input id="domain" type="text"
                                                       class="form-control @error('domain') is-invalid @enderror"
                                                       name="domain"
                                                       value="{{ $storedResource['domain'] ?? old('domain') }}" required
                                                       autocomplete="domain" autofocus>

                                                @error('domain')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="port"
                                                   class="col-md-4 col-form-label text-md-right">{{ __('port') }}</label>

                                            <div class="col-md-6">
                                                <input id="port" type="text"
                                                       class="form-control @error('port') is-invalid @enderror"
                                                       name="port" value="{{ $storedResource['port'] ?? old('port') }}"
                                                       autocomplete="port" autofocus>

                                                @error('port')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Submit') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
