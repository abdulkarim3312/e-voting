<style>
  .menu-sub {
    display: none;
  }

  .menu-item.open > .menu-sub {
    display: block;
  }

  .menu-item i {
    margin-right: 8px;
  }


  .menu-header {
      margin: 10px 0 !important;
      float: left;
      width: 100%;
  }

  .layout-menu-collapsed .menu-header {
      display: none;
  }

  .layout-menu-hover .menu-header {
      display: flex;
      margin: 10px 0 !important;
      float: left;
      width: 100%;
  }

.menu {
    display: flex;
    background-color: var(--bs-paper-bg);
    box-shadow: var(--bs-box-shadow) !important;
}

.help-box {
    border-radius: 5px;
    padding: 20px;
    margin: 65px 25px 25px;
    position: relative;
    background-color: rgba(245, 40, 145, 0.08);
    display: block;
}

.layout-menu-collapsed .help-box {
    display: none;
}

.layout-menu-hover .help-box {
    display: block;
}


.menu-inner-shadow {
    height: 2px;
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
      <li class="menu-header">
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


     <li class="menu-header mt-1">
        <span class="menu-header-text" data-i18n="Dashboard">ব্যবহারকারী</span>
      </li>


      @can('users-management',)
        @can('view-users')
          <li class="menu-item {{ $isUserActive ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
              <i class="fa-duotone fa-thin fa-users"></i>
              <div data-i18n="Dashboard">ব্যবহারকারী</div>
            </a>
          </li>
        @endcan

        @can('view-roles')
            <li class="menu-item {{ $isRoleActive ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class="menu-link">
                  <i class="fa-duotone fa-regular fa-user-shield"></i>
                    <div data-i18n="Blank Pages">ব্যবহারকারীর ভূমিকা</div>
                </a>
            </li>
        @endcan
      @endcan     


      <li class="menu-header">
        <span class="menu-header-text" data-i18n="Dashboard">অঞ্চল ব্যাবস্থাপনা</span>
      </li>
      @php
          $isDistrictActive = request()->routeIs('district.manage', 'district.create', 'district.edit');
          $isZoneActive = request()->routeIs('zone.manage', 'zone.create', 'zone.edit');
          $isAreaManagementOpen = $isDistrictActive || $isZoneActive;
      @endphp

      @if(in_array('district-management', session('permissions', [])))
        <li class="menu-item {{ request()->routeIs('district.manage') ? 'active' : '' }}">
            <a href="{{ route('district.manage') }}" class="menu-link">
              <i class="fa-duotone fa-light fa-list-radio"></i>
                <div>জেলা তালিকা</div>
            </a>
        </li>
      @endif
     
      @if(in_array('zone-management', session('permissions', [])))
          <li class="menu-item {{ request()->routeIs('zone.manage') ? 'active' : '' }}">
              <a href="{{ route('zone.manage') }}" class="menu-link">
                <i class="fa-duotone fa-light fa-list-tree"></i>
                  <div>নির্বাচনী জোন</div>
              </a>
          </li>
      @endif
     
      @if(in_array('office-manage', session('permissions', [])))
          <li class="menu-item {{ request()->routeIs('office.manage') ? 'active' : '' }}">
              <a href="{{ route('office.manage') }}" class="menu-link">
                <i class="fa-duotone fa-light fa-city"></i>
                  <div>কর্মস্থল</div>
              </a>
          </li>
      @endif


      <li class="menu-header">
        <span class="menu-header-text" data-i18n="Dashboard">কার্যনির্বাহী পরিষদ</span>
      </li>


      @php
          $isCategoryActive = request()->routeIs('category.manage', 'category.create', 'category.edit', 'designation.manage', 'designation.create', 'designation.edit', 'employee.manage');
      @endphp

      @if(in_array('zone-management', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('designation.manage') ? 'active' : '' }}">
                  <a href="{{ route('designation.manage') }}" class="menu-link">
                    <i class="fa-duotone fa-solid fa-user-tie"></i>
                      <div>কর্মকর্তা পদবী</div>
                  </a>
              </li>
              @endif
              @if(in_array('zone-management', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('category.manage') ? 'active' : '' }}">
                  <a href="{{ route('category.manage') }}" class="menu-link">
                    <i class="fa-duotone fa-solid fa-layer-group"></i>
                      <div>নির্বাচনী পদ</div>
                  </a>
              </li>
              @endif

              @if(in_array('district-create', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('candidate.manage') ? 'active' : '' }}">
                  <a href="{{ route('candidate.manage') }}" class="menu-link">
                    <i class="fa-duotone fa-thin fa-users-rays"></i>  
                    <div>নির্বাচনী প্রার্থী</div>
                  </a>
              </li>
              @endif
              @if(in_array('district-create', session('permissions', [])))
              <li class="menu-item {{ request()->routeIs('employee.manage') ? 'active' : '' }}">
                  <a href="{{ route('employee.manage') }}" class="menu-link">
                    <i class="fa-duotone fa-thin fa-users-rays"></i>  
                    <div>কর্মকর্তা তালিকা</div>
                  </a>
              </li>
              @endif


      <li>
        <div class="help-box text-center">
            <img src="{{ asset('userend/images/coffee-cup.svg') }}" height="90" alt="Helper Icon Image">
            <h5 class="mt-3 fw-semibold fs-16">Need Support?</h5>
            <p class="mb-3 text-muted">Contact with us for any technical support</p>
            <a href="https://solveitbd.com" target="_blank" class="btn btn-danger btn-sm">Contact</a>
        </div>
      </li>
            
    </ul>



</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
    <i class="ri ri-menu-line icon-base"></i>
    <i class="ri ri-arrow-right-s-line icon-base"></i>
  </a>
</div>




