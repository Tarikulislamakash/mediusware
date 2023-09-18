@extends('master')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 d-flex justify-content-between mt-5">
                <h4>
                    Name : {{ auth()->user()->name }}
                </h4>
                <h4>Current Balance : {{ auth()->user()->balance ?? 0 }}</h4>
            </div>

            <div class="col-md-8 d-flex justify-content-between mt-5">

                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Fee</th>
                        <th scope="col">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $transaction->amount ?? '' }}</td>
                                <td>{{ $transaction->transaction_type == 1 ? 'Deposit' : 'Withdraw' }}</td>
                                <td>{{ $transaction->fee ?? '' }}</td>
                                <td>{{ $transaction->date ?? '' }}</td>
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
