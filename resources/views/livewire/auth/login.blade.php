<form wire:submit="store" class="form w-100" novalidate="novalidate">
    @csrf
    <!--begin::Heading-->
    <div class="text-center mb-11">
        <!--begin::Title-->
        <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
        <!--end::Title-->
        <!--begin::Subtitle-->
        <!--end::Subtitle=-->
    </div>
    <!--end::Login options-->
    <!--begin::Separator-->
    <div class="separator separator-content my-14">
        <span class="w-125px text-gray-500 fw-semibold fs-7">With email</span>
    </div>
    <!--end::Separator-->
    <!--begin::Input group=-->
    <div class="fv-row mb-8">
        <!--begin::Email-->
        <input type="text" placeholder="Email" wire:model="email" autocomplete="off"
            class="form-control bg-transparent @error('email') is-invalid @enderror" />
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <!--end::Email-->
    </div>
    <!--end::Input group=-->
    <div class="fv-row mb-3">
        <!--begin::Password-->
        <input type="password" placeholder="Password" wire:model="password" autocomplete="off"
            class="form-control bg-transparent @error('password') is-invalid @enderror" />
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <!--end::Password-->
    </div>
    <div class="fv-row mb-8">
        <div class="captcha" wire:ignore>
            <span>{!! captcha_img() !!}</span>
            <button type="button" class="btn btn-danger" class="reload" id="reload">
                &#x21bb;
            </button>
        </div>
    </div>
    <div class="fv-row mb-8">
        <input id="captcha" type="text" class="form-control @error('captcha') is-invalid @enderror"
            placeholder="Enter Captcha" wire:model="captcha">
        @error('captcha')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <!--end::Input group=-->

    <!--begin::Submit button-->
    <div class="d-grid mb-10">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
            <!--begin::Indicator label-->
            <span class="indicator-label">Sign In</span>
            <!--end::Indicator label-->
            <!--begin::Indicator progress-->
            <span class="indicator-progress" wire:loading>
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            <!--end::Indicator progress-->
        </button>
    </div>
    <!--end::Submit button-->
</form>

@push('js')
    <script type="text/javascript">
        function getCaptcha() {
            $.ajax({
                type: 'GET',
                url: '{{ route('reload_captcha') }}',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        };

        $('#reload').click(function() {
            getCaptcha();
        });

        Livewire.on('reload-captcha', () => {
            getCaptcha();
        });
    </script>
@endpush
