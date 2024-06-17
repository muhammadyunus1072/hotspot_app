<form wire:submit="store">
    <div class='row mb-2'>
        <div class="col-md-12">
            <label class="form-label">Nama</label>
            <input type="text" placeholder="Nama" class="form-control @error('name') is-invalid @enderror" wire:model.blur="name" {{(($objId) ? ((!$isCanUpdate) ? 'disabled' : '') : '')}}/>
    
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }} 
                </div>
            @enderror
        </div>
    </div>
    <div class='row mb-2'>
        <div class="col-md-12">
            <label class="form-label">Deskripsi</label>
            <input type="text" placeholder="Deskripsi" class="form-control @error('description') is-invalid @enderror" wire:model.blur="description" {{(($objId) ? ((!$isCanUpdate) ? 'disabled' : '') : '')}}/>
    
            @error('description')
                <div class="invalid-feedback">
                    {{ $message }} 
                </div>
            @enderror
        </div>
    </div>
    <div class='row mb-2'>
        <div class="col-md-6">
            <label class="form-label">Harga</label>
            <input type="text" class="form-control currency @error('price') is-invalid @enderror" wire:model.blur="price" {{(($objId) ? ((!$isCanUpdate) ? 'disabled' : '') : '')}}/>
    
            @error('price')
                <div class="invalid-feedback">
                    {{ $message }} 
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Harga Sebelum Diskon</label>
            <input type="text" class="form-control currency @error('price_before_discount') is-invalid @enderror" wire:model.blur="price_before_discount" {{(($objId) ? ((!$isCanUpdate) ? 'disabled' : '') : '')}}/>
    
            @error('price_before_discount')
                <div class="invalid-feedback">
                    {{ $message }} 
                </div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-3 {{(($objId) ? ((!$isCanUpdate) ? 'd-none' : '') : '')}}">
        <i class='ki-duotone ki-check fs-1'></i>
        Save
    </button>
</form>

@include('js.imask')
