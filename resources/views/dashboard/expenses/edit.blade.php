@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
    <h1>Edit Expense</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" name="amount" id="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="Food" {{ old('category', $expense->category) == 'Food' ? 'selected' : '' }}>Food</option>
                <option value="Rent" {{ old('category', $expense->category) == 'Rent' ? 'selected' : '' }}>Rent</option>
                <option value="Travel" {{ old('category', $expense->category) == 'Travel' ? 'selected' : '' }}>Travel</option>
                <option value="Shopping" {{ old('category', 'Shopping') == 'Shopping' ? 'selected' : '' }}>Shopping</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $expense->date) }}" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes', $expense->notes) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Expense</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection