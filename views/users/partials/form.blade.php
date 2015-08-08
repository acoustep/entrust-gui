<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
  <label for="email">Email address</label>
  <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ $user->email }}">
</div>
<div class="form-group">
  <label for="password">Password</label>
  <input type="password" class="form-control" id="password" placeholder="Password" name="password">
</div>
<div class="form-group">
  <label for="roles">Roles</label>
  <select name="roles[]" multiple class="form-control">
    @foreach($roles as $index => $role)
      <option value="{{ $index }}" {{ ($user->roles->contains('id', $index)) ? 'selected' : '' }}>{{ $role }}</option>
    @endforeach
  </select>
</div>

