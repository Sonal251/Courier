@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            @if (session('status'))
                <div class="alert alert-success col-md-10" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <h3 class="col-md-8 text-left">
                                Forwarders
                            </h3>
                            <div class="col-md-4 text-right">
                                <button class="btn btn-outline-success" onclick="$('#addForwarder').modal()">
                                    &plus; Add
                                </button>
                            </div>
                        </div>
                        @if(auth()->user()->webhooks()->count())
                            <table class="table">
                                <tbody>
                                @foreach(auth()->user()->webhooks as $webhook)
                                    <tr>
                                        <td>{{ $webhook->to }}</td>
                                        <td>
                                            @if($webhook->paused)
                                                <del class="text-danger">{{ $webhook->url }}</del>
                                            @else
                                                {{ $webhook->url }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($webhook->created_at != $webhook->updated_at)
                                                {{ $webhook->updated_at->format('d M, H:i') }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($webhook->paused)
                                                <button class="btn btn-sm btn-success"
                                                        onclick="window.location.href='{{ route('webhook.enable', $webhook) }}'">
                                                    Re-enable
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger"
                                                    onclick="functions.destroy('{{ route('webhook.destroy', $webhook) }}')">
                                                &times;
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            You don't have any forwarders set up
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-md-10 mt-5">
                <div class="card">
                    <div class="card-body">
                        Your API Token<br>
                        <span class="font-weight-bold">{{ auth()->user()->api_token }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="addForwarder">
        <div class="modal-dialog" role="document">
            <form action="{{ route('webhook.store') }}" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Forwarder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    @csrf
                    <label class="col-md-4" for="to">Email Addressed To</label>
                    <input class="col-md-8" type="email" name="to" id="to" placeholder="example@email.com">

                    <label class="col-md-4" for="url">Notification URL</label>
                    <input class="col-md-8" type="text" name="url" id="url" placeholder="https://example.com/path">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
