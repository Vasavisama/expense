@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome to User Dashboard</h1>
    <p>You can manage your expenses from here.</p>
    <a href="{{ route('expenses.index') }}" class="btn btn-primary">Manage Expenses</a>
</div>
@endsection