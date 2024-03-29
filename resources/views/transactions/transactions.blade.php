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
                {!! Form::open(array('route' => 'transactions', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}
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
                <h5 class="float-left">Transaktionen</h5>

                <a href="{{  route('overwatch') }}" class="float-right">Zurück zu Overwatch</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Punkte</th>
                    <th>Positiv / Negativ</th>
                    <th>Begründung</th>
                    <th>Optionen</th>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr class="text-color-{{ $transaction->is_addition == 1 ? 'positiv' : 'negativ' }}">
                            <td>
                                @if($transaction->kid->scout_name)
                                    {{ $transaction->kid->scout_name }} / {{ $transaction->kid->first_name }} {{ $transaction->kid->last_name }}
                                @else
                                    {{ $transaction->kid->first_name }} {{ $transaction->kid->last_name }}
                                @endif
                            </td>
                            <td>
                                @if($transaction->kid->barcode != null)
                                    {{ $transaction->kid->barcode }}
                                @endif
                            </td>
                            <td>
                                {{ $transaction->points }}
                            </td>
                            <td>
                                {{ $transaction->is_addition == 1 ? 'Positiv' : 'Negativ' }}
                            </td>
                            <td>
                                {{ $transaction->reason }}
                            </td>
                            <td>
                                <button onclick="location.href='{{ route('edit-transactions', $transaction->id) }}'" class="btn btn-danger ml-2"><span class="fa fa-edit"></span></button>
                                <button onclick="location.href='{{ route('destroy-transactions', $transaction->id) }}'" class="btn btn-danger ml-2"><span class="fa fa-remove"></span></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
