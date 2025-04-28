<div class="col-md-12 row">
    <div class="col-md-4">
        <label class="form-label" for="search-permohonan">No Permohonan</label>
        <select class="form-control search-permohonan" style="width: 100%;" name="id_permohonan" id="id_permohonan">
            @if($filter['permohonan'] != null)
                <option value="{{ $filter['permohonan']->id }}">{{ $filter['permohonan']->no_permohonan }}</option>
            @endif
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="tipe layanan">Tipe Layanan</label>
        <select name="id_layanan" class="form-control" id="id_layanan">
            <option value="">-- Pilih Layanan --</option>
            @foreach ($layanan as $item)
                <option value="{{ $item->id_layanan }}"
                    {{ isset($filter['id_layanan']) && $filter['id_layanan'] == $item->id_layanan ? 'selected' : '' }}>
                    {{ strtoupper($item->nm_layanan) }}</option>
            @endforeach
        </select>
    </div>
</div>
