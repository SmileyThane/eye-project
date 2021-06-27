@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Resources
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th>IP</th>
                                    <th>is_active_ssl</th>
                                    <th>ssl_expired_at</th>
                                    <th>is_active</th>
                                    <th>last_status</th>
                                    <th>last_request_execution_time</th>
                                    <th>last_checked_at</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($storedResources as $storedResource)
                                    <tr>
                                        <td>
                                            <a href="{{route('find-stored-resource', ['id' => $storedResource->id])}}">
                                                {{ $storedResource->id }}
                                            </a>
                                        </td>
                                        <td>{{ $storedResource->name }}</td>
                                        <td>{{ $storedResource->protocol }}://{{ $storedResource->domain }}
                                            : {{ $storedResource->port }}</td>
                                        <td>{{ $storedResource->ip }}</td>
                                        <td>{{ $storedResource->is_active_ssl }}</td>
                                        <td>{{ $storedResource->ssl_expired_at }}</td>
                                        <td>{{ $storedResource->is_active }}</td>
                                        <td>{{ $storedResource->last_status }}</td>
                                        <td>{{ $storedResource->last_request_execution_time }}</td>
                                        <td>{{ $storedResource->last_checked_at }}</td>

                                        <td>
                                        <a class="btn btn-group btn-sm btn-success" href="{{route('check-status-info', ['id' => $storedResource->id])}}">
                                            <i class="fa fa-clipboard-list"></i>
                                        </a>
                                            <a class="btn btn-group btn-sm btn-danger" href="{{route('check-status-info', ['id' => $storedResource->id])}}">
                                                <i class="fa fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <a class="btn btn-primary" href="{{route('create-stored-resource')}}">Create</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
