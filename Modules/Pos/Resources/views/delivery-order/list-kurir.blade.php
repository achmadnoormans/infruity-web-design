<table class="table" id="kurir-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th class="text-center">Kurir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($staffs as $index => $staff)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $staff->name }}</td>
                <td class="text-center">
                    <input type="checkbox" name="kurir_ids[]" value="{{ $staff->id }}" class="form-check-input"
                        {{ $staff->is_kurir ? 'checked' : '' }}>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
