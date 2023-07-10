@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <form method="POST" action="{{route('admin.roles.update', $role->id)}}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{$role->name}}" readonly>
                        </div>
                        @foreach($permission as $value)
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck{{$value->id}}" name="permission[]" value="{{ $value->id }}" {{in_array($value->id, $rolePermissions) ? 'checked' : ''}}>
                                <label class="form-check-label" for="exampleCheck{{$value->id}}">{{ $value->name }}</label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn--primary">Update Role</button>
                    </form>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
