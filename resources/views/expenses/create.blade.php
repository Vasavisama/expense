@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Expense</h1>
    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" name="amount" class="form-control" id="amount" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" class="form-control" id="category" required>
                <option value="Food">Food</option>
                <option value="Rent">Rent</option>
                <option value="Travel">Travel</option>
                <option value="Shopping">Shopping</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" id="date" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" class="form-control" id="notes"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Expense</button>
    </form>
</div>
@endsection