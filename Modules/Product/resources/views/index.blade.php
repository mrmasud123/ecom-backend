@extends('layouts.app')

@section('content')
    <h2>Hello from products page</h2>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).ready(function(){
                console.log("From product module.");
            });
        });
    </script>
@endpush
