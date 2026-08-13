@extends('layouts.app')

@section('vendor-scripts')
    @vite([
        'Modules/Inventory/resources/assets/js/calc-batches.js'
    ]);
@endsection

@section('title', 'New Production Batch')

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'Inventory', 'link' => '#'], ['name' => 'Stock Production', 'link' => route('production-batches.index')], ['name' => 'New Batch', 'link' => '#']]" />

    <form action="{{ route('production-batches.store') }}" method="POST">
        @csrf
        @include('inventory::production-batches.partials._form')
    </form>

@endsection


