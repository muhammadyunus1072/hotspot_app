<form wire:submit="store">
    <div class='row mb-2'>
        <div class="col-md-12" wire:ignore>
            <label class="form-label">Member</label>

            <select class="form-select" id="select2-user">
                @if ($objId && $user_id)
                    <option value="{{$user_id}}">{{$user_text}}</option>
                @endif
            </select>
            @error('user_id')
                <div class="invalid-feedback">
                    {{ $message }} 
                </div>
            @enderror
        </div>
        <div class="col-md-12" wire:ignore>
            <label class="form-label">Paket Bulanan</label>

            <select class="form-select" id="select2-monthly-hotspot">
                @if ($objId && $product_id)
                    <option value="{{$product_id}}">{{$product_text}}</option>
                @endif
            </select>
            @error('product_id')
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

@push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {
            initSelect2();
        })

        function initSelect2() {
            $('#select2-user').select2({
                placeholder: "Pilih Member",
                ajax: {
                    url: "{{ route('hotspot_member.get.user') }}",
                    dataType: "json",
                    type: "GET",
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    "id": item.id,
                                    "text": item.text,
                                }
                            })
                        };
                    },
                },
                cache: true
            });
            $("#select2-user").on("select2:select", (e) => {
                let data = e.params.data;
                if (data) {
                    @this.call('addUser', data);
                }
            })

            $('#select2-monthly-hotspot').select2({
                placeholder: "Pilih Paket",
                ajax: {
                    url: "{{ route('hotspot_member.get.monthly_hotspot') }}",
                    dataType: "json",
                    type: "GET",
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    "id": item.id,
                                    "text": item.text,
                                }
                            })
                        };
                    },
                },
                cache: true
            });
            $("#select2-monthly-hotspot").on("select2:select", (e) => {
                let data = e.params.data;
                if (data) {
                    @this.call('addProduct', data);
                }
            })
        }
    </script>
@endpush
