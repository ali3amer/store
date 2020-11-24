<div class="sidebar">
    <div class="logo-area text-center">tto</div>
    <ul class="links-area list-unstyled">
        @if(Auth::user()->hasPermission('create_users') || Auth::user()->hasPermission('read_users'))
            <li>
                <router-link to="/users">المستخدمين</router-link>
            </li>
        @endif
        @if(Auth::user()->hasPermission('create_clients') || Auth::user()->hasPermission('read_clients'))
            <li>
                <router-link to="/clients">العملاء</router-link>
            </li>
        @endif
        @if(Auth::user()->hasPermission('create_categories') || Auth::user()->hasPermission('read_categories'))
            <li>
                <router-link to="/Categories">الأقسام</router-link>
            </li>
        @endif
        @if(Auth::user()->hasPermission('create_products') || Auth::user()->hasPermission('read_products'))
            <li>
                <router-link to="/products">المنتجات</router-link>
            </li>
        @endif
        @if(Auth::user()->hasPermission('create_orders') || Auth::user()->hasPermission('read_orders'))
            <li>
                <router-link to="/orders">المبيعات</router-link>
            </li>
        @endif
        <li>
            <router-link to="/reports">التقارير</router-link>
        </li>
        @if(Auth::user()->hasPermission('create_expenses') || Auth::user()->hasPermission('read_expenses'))
            <li>
                <router-link to="/expenses">المصروفات</router-link>
            </li>
        @endif
    </ul>
</div>
