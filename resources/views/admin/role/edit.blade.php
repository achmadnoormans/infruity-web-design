@extends('template.root')

@section('content')
    <div class="container">
        <h3>Edit Permissions for Role: {{ $role->nm_role }}</h3>

        <form action="{{ route('role-menu.update', $role->id_role) }}" method="POST">
            @csrf
            @method('PUT')

            @foreach ($groupedRoutes as $prefix => $routes)
                <div class="card mb-3">
                    <div class="card-header p-5">
                        <h3>{{ ucfirst($prefix) }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($routes as $route)
                                @php
                                    $method = Str::after($route, $prefix . '.');
                                @endphp
                                <div class="col-md-3">
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="{{ $route }}"
                                            {{ in_array($route, $rolePermissions) ? 'checked' : '' }}>
                                        {{ $method }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Save Permissions</button>
        </form>
    </div>
@section('script')
    <script>
        $("form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });
    </script>
@endsection
@endsection
