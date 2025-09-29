@extends('layouts.app')

@section('title', 'Import Expenses')

@section('content')
<div class="container">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Import Expenses</h2>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to Expenses</a>
    </div>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Import Form -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('expenses.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Choose CSV File</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>

    <div class="mt-4 alert alert-info">
        <h5 class="alert-heading">CSV File Format Instructions</h5>
        <p>Please ensure your CSV file has the following headers in the first row:</p>
        <code>amount,category,date,notes</code>
        <ul>
            <li><strong>amount:</strong> Numeric value for the expense amount (e.g., 50.25).</li>
            <li><strong>category:</strong> Must be one of the following: <strong>Food, Rent, Travel, Shopping</strong>.</li>
            <li><strong>date:</strong> Should be in <strong>dd/mm/yyyy</strong> format (e.g., 27/10/2023).</li>
            <li><strong>notes:</strong> Optional text for any notes.</li>
        </ul>
    </div>

</div>

<!-- Extra CSS -->
<style>
    .card {
        border-radius: 10px;
    }
    h2 {
        font-size: 1.6rem;
    }
    .btn {
        border-radius: 6px;
    }
</style>
@endsection