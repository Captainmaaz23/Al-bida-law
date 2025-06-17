<?php
$user_types_array = ['1' => 'Waiter', '2' => 'Table-User'];
$show_2nd_col = isset($team->image) ? 1 : 0; // Changed from photo to image
$cols_span = $show_2nd_col ? 'col-sm-6' : 'col-sm-8';
?>

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="{{ $cols_span }}">
        <div class="row">
            <div class="col-sm-4">
                <label for="name">Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ old('name', $team->name ?? '') }}">
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="image">Image:</label>
            </div>
            <div class="col-sm-8">
                <input type="file" name="image"  class="form-control">
                @error('image')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="title">User Title:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="title" placeholder="Title" class="form-control" value="{{ old('title', $team->title ?? '') }}">
                @error('title')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="bio">User Bio:</label>
            </div>
            <div class="col-sm-8">
                <textarea name="bio" placeholder="Bio" class="form-control">{{ old('bio', $team->bio ?? '') }}</textarea>
                @error('bio')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="position">User Position:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="position" placeholder="Position" class="form-control" value="{{ old('position', $team->position ?? '') }}">
                @error('position')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('our-team.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>

    @if ($show_2nd_col && isset($team->image))
        <div class="col-sm-6">
            <img id="image" src="{{ url('public/uploads/our-teams/'.$team->image) }}"
                 class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>