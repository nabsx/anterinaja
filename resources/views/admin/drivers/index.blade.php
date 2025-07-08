@extends('layouts.admin')

@section('title', 'Manage Drivers')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Driver Management</h5>
            <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Driver
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                    <tr>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->email }}</td>
                        <td>{{ $driver->phone }}</td>
                        <td>{{ $driver->vehicle_type }} ({{ $driver->vehicle_number }})</td>
                        <td>
                            @if($driver->verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @if(!$driver->verified)
                                <form action="{{ route('admin.drivers.verify', $driver) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Verify
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $drivers->links() }}
        </div>
    </div>
</div>
@endsection