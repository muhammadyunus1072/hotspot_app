@push('css')
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush
<div class="page-section">
    {{dd($a)}}
    <div class="page-separator">
        <div class="page-separator__text">{{ __('Transaction Histories') }}</div>
    </div>

    <div class="row card-group-row">
        @if (!empty($transaction))
            <div class="col-md-12">
                <div class="card card-sm">
                    <form wire:submit.prevent='save'>
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap col-md-7 col-12">
                                <tbody>
                                    <tr>
                                        <td class="card-title text-left">
                                            <p class="my-0 py-0">{{ __('Date') }}</p>
                                        </td>
                                        <td>:</td>
                                        <td colspan="3" class="card-title text-left">
                                            <p class="my-0 py-0">
                                                {{ Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="card-title text-left">
                                            <p class="my-0 py-0">{{ __('Number') }}</p>
                                        </td>
                                        <td>:</td>
                                        <td colspan="3" class="card-title text-left">
                                            <p class="my-0 py-0">
                                                {{ $transaction->number }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="card-title text-left">
                                            <p class="my-0 py-0">{{ __('Payment Method') }}</p>
                                        </td>
                                        <td>:</td>
                                        <td colspan="3" class="card-title text-left">
                                            <div class="my-0 py-0 d-inline">
                                                {{ $transaction->payment_method_name . '-' . $transaction->payment_method_description }}
                                            </div>
                                            @if ($transaction->payment_method_id != App\Models\PaymentMethod::MIDTRANS_ID)
                                                <button type="button" class='btn btn-info'
                                                    onclick="copyPaymentMethod()">
                                                    <i class='fa fa-copy'></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="card-title text-left">
                                            <p class="my-0 py-0">{{ __('Status') }}</p>
                                        </td>
                                        <td>:</td>
                                        <td colspan="3" class="card-title">
                                            {!! $transaction->last_status->get_beautify() !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-nowrap col-12">
                                <tbody>
                                    @foreach ($transaction->details as $transactionDetail)
                                        <tr>
                                            <td class="card-title text-left">
                                                <p class="my-0 py-0">{{ $transactionDetail->product_name }}</p>
                                            </td>
                                            <td class="card-title text-right">
                                                @if ($transactionDetail->product_price_before_discount)
                                                    <p class="my-0 py-0 d-inline mr-2">
                                                        <del>@currency($transactionDetail->product_price_before_discount)</del>
                                                    </p>
                                                @endif
                                                <p class="my-0 py-0 d-inline">
                                                    @currency($transactionDetail->product_price)
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="card-title text-right">
                                            <p class="my-0 py-0 h3">{{ __('TOTAL') }}</p>
                                        </td>
                                        <td class="card-title text-right">
                                            <p class="my-0 py-0 d-inline">
                                                @currency($transaction->details_sum_product_price)
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if (
                                $transaction->last_status->name != App\Models\TransactionStatus::STATUS_CANCEL &&
                                    $transaction->last_status->name != App\Models\TransactionStatus::STATUS_DONE)

                                @if ($transaction->payment_method_id == App\Models\PaymentMethod::MIDTRANS_ID)
                                    <div class="col-md-6 mx-auto">
                                        <button type="button" class="btn btn-block btn-success mb-2 w-100"
                                            wire:click="checkout">{{ __('Bayar') }}</button>
                                    </div>
                                @else
                                    <div class="row mx-2">
                                        <div class="col-md-12">

                                            {{-- FILE --}}
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="image">{{ __('Upload Proof of Payment') }} :</label>
                                                <div class="custom-file">
                                                    <input type="file" wire:model.lazy="image"
                                                        class="custom-file-input  @error('image') is-invalid @enderror">
                                                    <label for="image" class="custom-file-label">
                                                        <div wire:loading.remove wire:target="image">
                                                            @if ($image)
                                                                {{ $image->getClientOriginalName() }}
                                                            @else
                                                                {{ __('Pick Image') }}
                                                            @endif
                                                        </div>
                                                        <div wire:loading wire:target="image">
                                                            {{ __('Uploading...') }}
                                                        </div>
                                                    </label>
                                                    @error('image')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                @if ($image && empty($errors->get('image')))
                                                    <img class="img-fluid" src="{{ $image->temporaryUrl() }}"
                                                        style="width: 300px; height:auto">
                                                @elseif($oldImage != null)
                                                    <img class="img-fluid" src="{{ $oldImage }}"
                                                        style="width: 300px; height:auto">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-block btn-success mb-2">
                                                {{ __('Save Proof of Payment') }}</button>
                                            <button type='button' class='btn btn-block btn-danger mb-3'
                                                wire:click="confirmCancelTransaction()">
                                                <i class='fa fa-trash mr-2'></i> {{ __('Cancel Transaction') }}
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="col-md-12">
                <h3 class="text-center">{{ __('Empty') }}</h3>
            </div>
        @endif
    </div>
    <div id="snap-container"></div>
    <div class="card">
    </div>
</div>

@push('js')
    <script>
        Livewire.on('midtransCheckout', (snapToken) => {
            window.snap.pay(snapToken[0], {
                
                onSuccess: function(result) {
                    window.location.href = "{{ route('bill.index') }}";
                },
                onError: function(result) {
                    Livewire.emit('onFailSweetAlert', "{{ __('Payment Fail') }}");
                },
                onClose: function() {
                    Livewire.emit('onFailSweetAlert', "{{ __('Payment Closed') }}");
                }
            });
        });
        
        window.addEventListener('openConfirmCancellationModal', event => {
            if (confirm('Batalkan Transaksi?')) {
                @this.cancelTransaction();
            }
        });

        function copyPaymentMethod() {
            // Copy the text inside the text field
            navigator.clipboard.writeText("{{ $transaction->payment_method_description }}");

            // Alert the copied text
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ __('Account Number Copied') }}",
            });
        }
    </script>
@endpush
