<form wire:submit="store">
    <div class='row mb-2'>
            <div class="col-md-12 row mt-3">
                <div class="row d-flex justify-content-start ms-3">
                    <div class="col-md-6 mb-3">
                        <label class="mb-1">Nama</label>
                        <h3 class="form-control">{{ $user_text }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 row mt-3" wire:key="transaction_details">
                @foreach ($transaction_details as $index => $item)
                    <div class="mb-3 row d-flex justify-content-start ms-3">
                        <div class="col-md-6 mb-3">
                            <label class="mb-1">Nama Produk</label>
                            <h3 class="form-control">{{ $item['text'] }}</h3>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="mb-1">Jumlah</label>
                            <h3 class="form-control">{{ $item['qty'] }}</h3>
                        </div>
                        <div class="col"></div>
                        <hr>
                    </div>
                @endforeach
            </div>
            @if ($status == \App\Models\TransactionStatus::STATUS_DONE || $status == \App\Models\TransactionStatus::STATUS_CANCEL)
                
                <div class="col-md-12 row mt-3">
                    <div class="row d-flex justify-content-start ms-3">
                        <div class="col-md-6 mb-3">
                            <label class="mb-1">Metode Pembayaran</label>
                            <h3 class="form-control">{{ $payment_method_name }}</h3>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-start ms-3">
                        <div class="col-md-6 mb-3">
                            <label class="mb-1">Status</label>
                            <h3 class="form-control">{{ $status }}</h3>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-6" wire:ignore>
                    <div class="row d-flex justify-content-start ms-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                
                            <select class="form-select" id="select2-payment-method">
                            </select>
                            @error('payment_method_id')
                                <div class="invalid-feedback">
                                    {{ $message }} 
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                @if (\App\Helpers\UserHelper::role() == \App\Models\User::ROLE_ADMIN)
                    <div class="col-md-12 row mt-3">
                        <div class="row d-flex justify-content-start ms-3">
                            <div class="col-md-6 mb-3">
                                <label class="mb-1">Status</label>
                                
                                <select class="form-select" wire:model.blur="status">
                                    @foreach (\App\Models\TransactionStatus::STATUS_CHOICE as $key => $name)
                                        <option value="{{$key}}" {{($key == $status) ? "selected" : ""}}>{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

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
            
            $('#select2-payment-method').select2({
                placeholder: "Pilih Metode Pembayaran",
                ajax: {
                    url: "{{ route('bill.get.payment_method') }}",
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
                                    "description": item.description,
                                }
                            })
                        };
                    },
                },
                cache: true
            });
            $("#select2-payment-method").on("select2:select", (e) => {
                let data = e.params.data;
                if (data) {
                    @this.call('addPaymentMethod', data);
                }
            })


        }
    </script>
@endpush

