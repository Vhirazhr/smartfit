<h2>Tambah Latihan</h2>

<form method="POST" action="/admin/exercise/store">
    @csrf
    <input type="text" name="name" placeholder="Nama"><br><br>
    <textarea name="description" placeholder="Deskripsi"></textarea><br><br>
    <button type="submit">Simpan</button>
</form>