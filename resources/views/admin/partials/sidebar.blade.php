@php
    $active = $active ?? '';
@endphp

<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <h2>SMARTfit</h2>
        <span>Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <p class="sidebar-section-title">Menu Utama</p>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ $active === 'dashboard' ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.smartfit-analytics.index') }}" class="{{ $active === 'smartfit-analytics' ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        SmartFIT Analytics
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-section">
            <p class="sidebar-section-title">Manajemen Data</p>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.fashion-categories.index') }}" class="{{ $active === 'fashion-categories' ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.fashion-items.index') }}" class="{{ $active === 'fashion-items' ? 'active' : '' }}">
                        <i class="fas fa-tshirt"></i>
                        Semua Fashion
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
