<div class="row">
  <div class="col-sm-12">
    @if ($errors->has())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach
    </div>
    @endif
  </div>
</div>

<form action="{{ url('time-tracker-save') }}" method="POST">
  <div class="row">
    <div class="col-sm-8">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <label>Description</label>
        <input type="text" name="desc"
        id="desc" placeholder="Enter the Time Entry Description"
        class="form-control" value="{{ old('desc') }}" />
      </div>

      <div class="form-group">
        <label>Project</label>
        <select class="form-control" name="project_id" id="project-select">
          <option>Select Project</option>
          @foreach($projects as $project)
          <option value="{{ $project->id }}">
            {{ $project->client->name }} => {{ $project->name }}
          </option>
          @endforeach

        </select>
      </div>

      <div class="form-group" id="estimate-wrapper"></div>

      <div class="form-group">
        <label>Hours</label>

        <input type="number" name="time"
        id="time" placeholder="Hours"
        class="form-control" value="{{ old('time') }}"
        step="any" />
      </div>

      <button class="btn btn-primary">Save</button>
    </div>

    <div class="col-sm-4">
      <div class="form-group">
        <label>Tags</label>
        <br>
        @foreach ($tags as $tag)
        <input type="checkbox" name="tags[]" value="{{$tag->id}}" /> {{$tag->name}} <br>
        @endforeach
      </div>
    </div>
  </div>
</form>
