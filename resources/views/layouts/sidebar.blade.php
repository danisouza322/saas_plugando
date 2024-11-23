<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('painel.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('painel.dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu Principal</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('painel.dashboard') ? 'active' : '' }}"
                        href="{{ route('painel.dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> 
                        <span>Painel de Controle</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('painel.clientes.*') ? 'active' : '' }}"
                        href="{{ route('painel.clientes.index') }}">
                        <i class="ri-user-line"></i>
                        <span>Clientes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('painel.certificados.*') ? 'active' : '' }}"
                        href="{{ route('painel.certificados.index') }}">
                        <i class="ri-shield-keyhole-line"></i>
                        <span>Certificados</span>
                    </a>
                </li>

                @if(auth()->user()->hasRole('super-admin'))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarGerencial" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarGerencial">
                        <i class="ri-admin-line"></i> <span>Gerencial</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarGerencial">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('painel.gerencial.usuarios.index') }}" class="nav-link">
                                    Usuários
                                </a>
                            </li>
                            @if(auth()->id() === 1)
                            <li class="nav-item">
                                <a href="{{ route('painel.gerencial.empresas.index') }}" class="nav-link">
                                    Empresas
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
