@extends('master')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 d-flex justify-content-between mt-5">
                <h4>
                    Name : {{ auth()->user()->name }}
                </h4>
                <h4>Current Balance : {{ auth()->user()->balance ?? 0 }}</h4>
                <a class="btn btn-primary" href="{{ route('withdraw') }}">Withdraw</a>
            </div>

            <div class="col-md-8 d-flex justify-content-between mt-5">
                @if(Session::has('withdraw_success'))
                    <p class="alert alert-info">{{ Session::get('withdraw_success') }}</p>
                @endif
                @if(Session::has('withdraw_failed'))
                    <p class="alert alert-danger">{{ Session::get('withdraw_failed') }}</p>
                @endif
            </div>

            <div class="col-md-8 d-flex justify-content-between mt-5">

                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Fee</th>
                        <th scope="col">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdraws as $withdraw)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $withdraw->amount ?? '' }}</td>
                                <td>{{ $withdraw->fee ?? '' }}</td>
                                <td>{{ $withdraw->date ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-danger" colspan="5">No transaction found</td>
                            </tr>
                        @endforelse
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
