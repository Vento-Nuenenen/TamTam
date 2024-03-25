@extends('layouts.app')

@section('content')
    <div class="col-12">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                {!! Form::open(array('route' => 'points', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}
                <div class="input-group" id="adv-search">
                    {!! Form::text('search', NULL, array('id' => 'search', 'class' => 'form-control', 'placeholder' => 'Suche', 'autofocus')) !!}
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary form-control">
                            <span class="fa fa-search"></span>
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="input-group" id="adv-search">
                    <button onclick="location.href='{{ route('add-transactions') }}'" type="button" class="btn btn-primary form-control mt-2">Neue Transaktion</button>
                </div>
            </div>
        </div>

        <br />

        <div class="card">
            <div class="card-header">
                <h5 class="float-left">Punkte</h5>

                <a href="{{  route('overwatch') }}" class="float-right">Zurück zu Overwatch</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Punkte</th>
                    </thead>
                    <tbody>
                    @foreach($kids as $kid)
                        <tr>
                            <td>
                                @if($kid->scout_name)
                                    {{ $kid->first_name }} {{ $kid->last_name }} / {{ $kid->scout_name }}
                                @else
                                    {{ $kid->first_name }} {{ $kid->last_name }}
                                @endif
                            </td>
                            <td>
                                @if($kid->barcode != null)
                                    {{ $kid->barcode }}
                                @endif
                            </td>
                            <td>
                                @if($kid->current_balance > 0)
                                    <span class="badge badge-success">{{ $kid->current_balance }}</span>
                                @elseif($kid->current_balance < 0)
                                    <span class="badge badge-danger">{{ $kid->current_balance }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ $kid->current_balance }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
