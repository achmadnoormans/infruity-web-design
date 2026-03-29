<table class="table align-middle table-row-bordered gy-3" id="kurir-table">
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
                <td>
                    <span class="badge badge-light-secondary fw-semibold">{{ $index + 1 }}</span>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-gray-900">{{ $staff->name }}</span>
                        <span class="fs-8 text-muted">Siap dipilih sebagai kurir</span>
                    </div>
                </td>
                <td class="text-center">
                    <div class="form-check form-check-custom form-check-solid d-inline-flex justify-content-center">
                        <input type="checkbox" name="kurir_ids[]" value="{{ $staff->id }}" class="form-check-input"
                            {{ $staff->is_kurir ? 'checked' : '' }}>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
