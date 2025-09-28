@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Expense</h1>
    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" name="amount" class="form-control" id="amount" value="{{ $expense->amount }}" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" class="form-control" id="category" required>
                <option value="Food" {{ $expense->category == 'Food' ? 'selected' : '' }}>Food</option>
                <option value="Rent" {{ $expense->category == 'Rent' ? 'selected' : '' }}>Rent</option>
                <option value="Travel" {{ $expense->category == 'Travel' ? 'selected' : '' }}>Travel</option>
                <option value="Shopping" {{ $expense->category == 'Shopping' ? 'selected' : '' }}>Shopping</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" id="date" value="{{ $expense->date }}" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" class="form-control" id="notes">{{ $expense->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Expense</button>
    </form>
</div>
@endsection