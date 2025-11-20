@extends('layouts.sidebar')

@section('content')

<form action="{{ route('suppliers.store') }}" method="POST">
    @csrf

    @php($supplier = null)
    @include('suppliers._form')

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

@endsection
