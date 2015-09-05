<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ (Session::has('errors')) ? old('email', '') : $user->email }}">
</div>
<div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
    @if(Route::currentRouteName() == 'entrust-gui::users.edit')
        <div class="alert alert-info">
          <span class="fa fa-info-circle"></span> Leave the password field blank if you wish to keep it the same.
        </div>
    @endif
</div>
<div class="form-group">
    <label for="roles">Roles</label>
    <select name="roles[]" multiple class="form-control">
        @foreach($roles as $index => $role)
            <option value="{{ $index }}" {{ ((in_array($index, old('roles', []))) || ( ! Session::has('errors') && $user->roles->contains('id', $index))) ? 'selected' : '' }}>{{ $role }}</option>
        @endforeach
    </select>
</div>
