<form wire:submit="store">
    <div class='row'>
        <div class="col-md-6 mb-4">
            <label>Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.blur="name" />

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-6 mb-4">
            <label>Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.blur="email" />

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-6 mb-4">
            <label>Phone</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.blur="phone" />

            @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-12 mb-4">
            <label>Jabatan</label>
            <select class="form-select @error('role') is-invalid @enderror" wire:model.blur="role">
                @foreach ($roles as $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
            </select>

            @error('type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-12 mb-4">
            <label>Password</label>
            @if ($objId)
                <div class='fst-italic'>*Diisi jika ingin mengubah password</div>
            @endif
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                wire:model.blur="password" />

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

    </div>

    <button type="submit" class="btn btn-success mt-3">
        <i class='ki-duotone ki-check fs-1'></i>
        Save
    </button>
</form>
