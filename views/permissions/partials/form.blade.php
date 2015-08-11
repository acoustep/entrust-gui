<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
  <label for="name">Name</label>
  <input type="input" class="form-control" id="name" placeholder="Name" name="name" value="{{ $permission->name }}">
</div>
<div class="form-group">
  <label for="display_name">Display Name</label>
  <input type="input" class="form-control" id="display_name" placeholder="Display Name" name="display_name" value="{{ $permission->display_name }}">
</div>
<div class="form-group">
  <label for="description">Description</label>
  <input type="input" class="form-control" id="description" placeholder="Description" name="description" value="{{ $permission->description }}">
</div>
