@extends('master')

@section('content')
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 mt-5">
                <form method="POST" action="{{ route('depositSubmit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Ammount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" aria-describedby="amount" required>
                    </div>
                    @error('amount')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary">Deposit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
