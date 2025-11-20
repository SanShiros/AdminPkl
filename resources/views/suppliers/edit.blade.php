@extends('layouts.sidebar')

@section('content')

<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
    @csrf
    @method('PUT')

    @include('suppliers._form')

    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection
