<h2>Edit Latihan</h2>

<form method="POST" action="/admin/exercise/update/{{ $data->id }}">
    @csrf
    <input type="text" name="name" value="{{ $data->name }}"><br><br>
    <textarea name="description">{{ $data->description }}</textarea><br><br>
    <button type="submit">Update</button>
</form>
