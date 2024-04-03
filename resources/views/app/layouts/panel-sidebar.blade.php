<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">
                @foreach (App\Helpers\MenuHelper::menu() as $menu)
                    @if (isset($menu['submenu']))
                        <!--begin:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item here menu-accordion {{ $menu['is_active'] ? 'show' : '' }}">
                            <!--begin:Menu link-->
                            <span class="menu-link {{ $menu['is_active'] ? 'active' : '' }}">
                                @if (isset($menu['icon']))
                                    <span class="menu-icon">
                                        <i class="{{ $menu['icon'] }} fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                @endif
                                <span class="menu-title">{{ $menu['text'] }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                @foreach ($menu['submenu'] as $submenu)
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link {{ $submenu['is_active'] ? 'active' : '' }}"
                                            href="{{ route($submenu['route']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ $submenu['text'] }}</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                @endforeach
                            </div>
                            <!--end:Menu sub-->
                        </div>
                        <!--end:Menu item -->
                    @else
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ $menu['is_active'] ? 'active' : '' }}"
                                href="{{ route($menu['route']) }}">
                                @if (isset($menu['icon']))
                                    <span class="menu-icon">
                                        <i class="{{ $menu['icon'] }} fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                @endif
                                <span class="menu-title">{{ $menu['text'] }}</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endif
                @endforeach
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
