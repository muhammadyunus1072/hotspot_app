<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xxl-12">
        <!--begin::Chart widget 22-->
        <div class="card h-xl-100">
            <!--begin::Body-->
            <div class="card-body pb-3">
                <!--begin::Tab Content-->
                <div class="tab-content">
                    <!--begin::Tap pane-->
                    <div class="tab-pane fade show active" id="kt_chart_widgets_22_tab_content_1" role="tabpanel" aria-labelledby="kt_chart_widgets_22_tab_1">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-wrap flex-md-nowrap row">
                            <!--begin::Items-->
                            <div class="col-md-6 row align-items-center">
                                <!--begin::Item-->
                                <div class="d-flex border border-gray-300 border-dashed rounded p-6 mb-6">
                                    <!--begin::Block-->
                                    <div class="d-flex align-items-center flex-grow-1 me-2 me-sm-5">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-50px me-4">
                                            <span class="symbol-label">
                                                <i class="ki-duotone ki-timer fs-2qx text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Section-->
                                        <div class="me-2">
                                            <p class="text-gray-800 fs-5 fw-bold">Tagihan Paket Bulanan</p>
                                            <p class="text-gray-800 fs-5 fw-bold">{{ $product_name }} - {{$product_price }}</p>
                                            <span class="text-gray-400 fw-bold d-block fs-7">{{ $product_description }}</span>
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Block-->
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center">
                                        <span class="text-dark fw-bolder fs-2x">{{ $date }}</span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Items-->
                            <!--begin::Container-->
                            @if ($monthly_hotspot_status == \App\Models\TransactionStatus::STATUS_DONE)
                                <div class="col-md-4 d-flex justify-content-start align-items-center row mx-md-0">
                                        <h1 class="fw-bolder text-success display-4 col-12 bg-light rounded">Lunas</h1>
                                        
                                </div>
                            @else
                                <div class="col-md-4 d-flex justify-content-start align-items-center row mx-md-0">
                                    @if ($product_name)
                                        <h1 class="fw-bolder text-warning display-4">Belum Lunas</h1>    
                                    @else
                                        <h1 class="fw-bolder text-warning display-4">Belum Terdaftar</h1>
                                    @endif
                                </div>
                            @endif
                            <!--end::Container-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Tap pane-->
                </div>
                <!--end::Tab Content-->
            </div>
            <!--end: Card Body-->
        </div>
        <!--end::Chart widget 22-->
    </div>
    <!--end::Col-->
</div>