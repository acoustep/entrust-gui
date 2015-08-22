<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
  <label for="name">Name</label>
  <input type="input" class="form-control" id="name" placeholder="Name" name="name" value="{{ $model->name }}">
</div>
<div class="form-group">
  <label for="display_name">Display Name</label>
  <input type="input" class="form-control" id="display_name" placeholder="Display Name" name="display_name" value="{{ $model->display_name }}">
</div>
<div class="form-group">
  <label for="description">Description</label>
  <input type="input" class="form-control" id="description" placeholder="Description" name="description" value="{{ $model->description }}">
</div>
<div class="form-group">
  <label for="{{str_plural($relation_name) }}">{{ ucwords(str_plural($relation_name))}}</label>
  <select name="{{str_plural($relation_name) }}[]" multiple class="form-control">
    @foreach($relations as $index => $relation)
      <option value="{{ $index }}" {{ ($model->{$short_relation_name}->contains('id', $index)) ? 'selected' : '' }}>{{ $relation }}</option>
    @endforeach
  </select>
</div>
