@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <form method="POST" action="{{route('admin.admins.update', $user->id)}}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" class="form-control" value="{{$user->name}}" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="text" name="email" class="form-control" value="{{$user->email}}" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="text" name="username" class="form-control" value="{{$user->username}}" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="text" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Confirm Password</label>
                            <input type="text" name="confirm-password" class="form-control" placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <label for="role">{{ __('Role')}}</label>
                            <select name="roles[]" id="role" class="form-control">
                                @foreach ($roles as $role)
                                    <option value="{{$role}}" {{in_array($role, $userRole) ? 'selected' : ''}}>{{$role}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn--primary">Update Admin</button>
                    </form>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
