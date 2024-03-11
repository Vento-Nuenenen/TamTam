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
                <h5 class="float-left">TN Bestanden</h5>

                <a href="{{  route('overwatch') }}" class="float-right">Zurück zu Overwatch</a>
            </div>
            <div class="card-body table-responsive">
                {!! Form::open(array('route' => 'do-passed', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}
                {!! csrf_field() !!}
                <table class="table table-hover">
                    <thead>
                    <th>Name</th>
                    <th>Hat Bestanden</th>
                    </thead>
                    <tbody>
                    @foreach($kids as $kid)
                        <tr>
                            <td>
                                @if($kid->scout_name)
                                    {{ $kid->scout_name }} / {{ $kid->first_name }} {{ $kid->last_name }}
                                @else
                                    {{ $kid->first_name }} {{ $kid->last_name }}
                                @endif
                            </td>
                            <td>
                                <input type="checkbox" name="has_passed[]" value="{{ $kid->id }}" {{ isset($kid->course_passed) && $kid->course_passed == 1 ? 'checked' : '' }} />
                                <input type="hidden" name="not_passed[]" value="{{ $kid->id }}" />
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary form-control mt-2">Einstellungen eintragen</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
