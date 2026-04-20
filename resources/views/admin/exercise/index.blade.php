<h2>Data Latihan</h2>

<a href="/admin/exercise/create">+ Tambah Data</a>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>

    @foreach($data as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->name }}</td>
        <td>{{ $d->description }}</td>
        <td>
            <a href="/admin/exercise/edit/{{ $d->id }}">Edit</a> |
            <a href="/admin/exercise/delete/{{ $d->id }}">Hapus</a>
        </td>
    </tr>
    @endforeach
</table>