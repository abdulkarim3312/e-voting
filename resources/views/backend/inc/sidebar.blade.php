<style>
  .menu-sub {
    display: none;
  }

  .menu-item.open > .menu-sub {
    display: block;
  }
</style>
<aside id="layout-menu" class="layout-menu menu-vertical menu" >
  <div class="app-brand demo ">
    <a href="/" class="app-brand-link gap-xl-0 gap-2">
      <span class="app-brand-logo demo me-1">
        <span class="text-primary">
            <img src="{{ asset('lib/img/logo.webp') }}" alt="Logo" style="width: 35px; border-radius: 50px;">
        </span>
    </span>
      <span class="app-brand-text demo menu-text fw-semibold ms-2">Sovle IT</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

      <!-- Dashboard Header -->
      <li class="menu-header mt-7">
        <span class="menu-header-text" data-i18n="Dashboard">ড্যাশবোর্ড</span>
      </li>


      <!-- Dashboard Menu -->
      <li class="menu-item">
        <a href="{{ route('dashboard') }}" class="menu-link">
          <i class="menu-icon icon-base ri ri-home-smile-line"></i>
          <div data-i18n="Dashboards">ড্যাশবোর্ড</div>
        </a>
      </li>
      @php
        $isUserActive = request()->routeIs('users.index', 'users.create', 'users.edit');
        $isRoleActive = request()->routeIs('roles.index', 'roles.create', 'roles.edit');
        $isUserManagementOpen = $isUserActive || $isRoleActive;
      @endphp

      @can('users-management',)
        <li class="menu-item {{ $isUserManagementOpen ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon icon-base ri ri-user-3-line"></i>
            <div data-i18n="Dashboards">ব্যবহারকারী ব্যবস্থাপনা</div>
          </a>
          <ul class="menu-sub">
            @can('view-users')
            <li class="menu-item {{ $isUserActive ? 'active' : '' }}">
              <a href="{{ route('users.index') }}" class="menu-link">
                <div data-i18n="Dashboard">ব্যবহারকারী</div>
              </a>
            </li>
            @endcan

            @can('view-roles')
                <li class="menu-item {{ $isRoleActive ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                        <div data-i18n="Blank Pages">ভূমিকা</div>
                    </a>
                </li>
            @endcan
          </ul>
        </li>
      @endcan
        
      <!-- category Manage Menu -->
      @php
          $isCategoryActive      = request()->routeIs('categories.index', 'categories.create', 'categories.edit');
          $isSubCategoryActive   = request()->routeIs('sub-categories.index', 'sub-categories.create', 'sub-categories.edit');
          $isChildCategoryActive = request()->routeIs('child-categories.index', 'child-categories.create', 'child-categories.edit');

          $isCategoryManagementOpen = $isCategoryActive || $isSubCategoryActive || $isChildCategoryActive;
      @endphp

      @if(in_array('category.manage', session('permissions', [])))
          <li class="menu-item {{ $isCategoryManagementOpen ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon fas fa-layer-group fs-5"></i>
                  <div data-i18n="Category">Category Manage</div>
              </a>
              <ul class="menu-sub">

                  @if(in_array('category.view', session('permissions', [])))
                      <li class="menu-item {{ $isCategoryActive ? 'active' : '' }}">
                          <a href="{{ route('categories.index') }}" class="menu-link">
                              <div>Category</div>
                          </a>
                      </li>
                  @endif

                  @if(in_array('subcat.view', session('permissions', [])))
                      <li class="menu-item {{ $isSubCategoryActive ? 'active' : '' }}">
                          <a href="{{ route('sub-categories.index') }}" class="menu-link">
                              <div>Sub Category</div>
                          </a>
                      </li>
                  @endif

                  @if(in_array('childcat.view', session('permissions', [])))
                      <li class="menu-item {{ $isChildCategoryActive ? 'active' : '' }}">
                          <a href="{{ route('child-categories.index') }}" class="menu-link">
                              <div>Child Category</div>
                          </a>
                      </li>
                  @endif

              </ul>
          </li>
      @endif
      
     

      @php
          $isDistrictActive = request()->routeIs('district.manage', 'district.create', 'district.edit');
      @endphp

      @if(in_array('district-management', session('permissions', [])))
      <li class="menu-item {{ $isDistrictActive ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon fas fa-map-marker-alt"></i>
              <div data-i18n="District">জেলা ব্যবস্থাপনা</div>
          </a>
          <ul class="menu-sub">
              @if(in_array('district-management', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('district.manage') ? 'active' : '' }}">
                  <a href="{{ route('district.manage') }}" class="menu-link">
                      <div>জেলা তালিকা দেখুন</div>
                  </a>
              </li>
              @endif

              @if(in_array('district-create', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('district.create') ? 'active' : '' }}">
                  <a href="{{ route('district.create') }}" class="menu-link">
                      <div>জেলা যোগ করুন</div>
                  </a>
              </li>
              @endif
          </ul>
      </li>
      @endif
     

      @php
          $isDistrictActive = request()->routeIs('zone.manage', 'zone.create', 'zone.edit');
      @endphp

      @if(in_array('zone-management', session('permissions', [])))
      <li class="menu-item {{ $isDistrictActive ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon fas fa-chart-area"></i>
              <div data-i18n="Zone">নির্বাচনী জোন</div>
          </a>
          <ul class="menu-sub">
              @if(in_array('zone-management', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('zone.manage') ? 'active' : '' }}">
                  <a href="" class="menu-link">
                      <div>নির্বাচনী জোন দেখুন</div>
                  </a>
              </li>
              @endif

              @if(in_array('district-create', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('district.create') ? 'active' : '' }}">
                  <a href="" class="menu-link">
                      <div>জোন যোগ করুন</div>
                  </a>
              </li>
              @endif
          </ul>
      </li>
      @endif

      @php
          $isCategoryActive = request()->routeIs('category.manage', 'category.create', 'category.edit');
      @endphp

      @if(in_array('zone-management', session('permissions', [])))
      <li class="menu-item {{ $isCategoryActive ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon fas fa-chart-area"></i>
              <div data-i18n="Zone">Candidate Manage</div>
          </a>
          <ul class="menu-sub">
              @if(in_array('zone-management', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('zone.manage') ? 'active' : '' }}">
                  <a href="{{ route('category.manage') }}" class="menu-link">
                      <div>Category</div>
                  </a>
              </li>
              @endif

              @if(in_array('district-create', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('district.create') ? 'active' : '' }}">
                  <a href="" class="menu-link">
                      <div>Candidate</div>
                  </a>
              </li>
              @endif
          </ul>
      </li>
      @endif
    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
    <i class="ri ri-menu-line icon-base"></i>
    <i class="ri ri-arrow-right-s-line icon-base"></i>
  </a>
</div>
