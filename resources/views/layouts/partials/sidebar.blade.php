<!-- Sidebar  -->
<nav id="sidebar" class="p-3 mb-2 bg-secondary text-white">
    <div class="sidebar-header components">
        <a href="{{ route('home') }}">
            <h4 class="text-uppercase">
                <i class="fas fa-fan fa-spin mr-2"></i>{{ trans('messages.sidebar.header') }}
            </h4>
            <strong><i class="fas fa-fan fa-spin mr-2"></i>NR</strong>
        </a>
    </div>
    @if(Auth()->user()->role  == 'admin')
    <!-- Managemnet -->
    <ul class="list-unstyled components">
        <!-- Managemnet header -->
        <h4 class="text-uppercase">{{ trans('messages.sidebar.management_header') }}</h4>

        <!-- Account -->
        <li>
            <a href="#accountSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-user-ninja mr-2"></i>{{ trans('messages.sidebar.account.header') }}
            </a>
            <ul class="collapse list-unstyled" id="accountSidebar">
                <li>
                    <a href="{{ route('create-account') }}">
                        <i class="fas fa-user-plus mr-2"></i>{{ trans('messages.sidebar.account.create') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('account-list') }}">
                        <i class="fas fa-users mr-2"></i>{{ trans('messages.sidebar.account.list') }}
                    </a>
                </li>
            </ul>
        </li>

        <!-- Food -->
        <li>
            <a href="#foodSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-utensils mr-2"></i>{{ trans('messages.sidebar.food.header') }}
            </a>
            <ul class="collapse list-unstyled" id="foodSidebar">
                <li>
                    <a href="{{ route('create-food') }}">
                        <i class="fas fa-plus mr-2"></i>{{ trans('messages.sidebar.food.create') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('food-list') }}">
                        <i class="fas fa-list-ul mr-2"></i>{{ trans('messages.sidebar.food.list') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    @elseif(Auth()->user()->role  == 'receptionist')
    <!-- Managemnet -->
    <ul class="list-unstyled components">
        <!-- Managemnet header -->
        <h4 class="text-uppercase">{{ trans('messages.sidebar.management_header') }}</h4>

        <!-- Bill -->
        <li>
            <a href="#billSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-file-alt mr-2"></i>{{ trans('messages.sidebar.bill.header') }}
            </a>
            <ul class="collapse list-unstyled" id="billSidebar">
                <li>
                    <a href="{{ route('bill-list') }}">
                        <i class="fas fa-copy mr-2"></i>{{ trans('messages.sidebar.bill.list') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    @elseif(Auth()->user()->role  == 'waiter')
    @elseif(Auth()->user()->role  == 'kitchen_manager')
    @elseif(Auth()->user()->role  == 'accountant')
    <!-- Managemnet -->
    <ul class="list-unstyled components">
        <!-- Managemnet header -->
        <h4 class="text-uppercase">{{ trans('messages.sidebar.management_header') }}</h4>

        <!-- Deposit -->
        <li>
            <a href="#depositSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-file-invoice-dollar mr-2"></i>{{ trans('messages.sidebar.deposit.header') }}
            </a>
            <ul class="collapse list-unstyled" id="depositSidebar">
                <li>
                    <a href="{{ route('create-deposit') }}">
                        <i class="fas fa-file-signature mr-2"></i>{{ trans('messages.sidebar.deposit.get') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('repay-deposit') }}">
                        <i class="fas fa-file-invoice mr-2"></i>{{ trans('messages.sidebar.deposit.repay') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('deposit-bill') }}">
                        <i class="fas fa-copy mr-2"></i>{{ trans('messages.sidebar.deposit.list') }}
                    </a>
                </li>
            </ul>
        </li>

        <!-- Export Excel -->
        <li>
            <a href="#exportSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-file-export mr-2"></i>{{ trans('messages.sidebar.export.header') }}
            </a>
            <ul class="collapse list-unstyled" id="exportSidebar">
                <li>
                    <a href="{{ route('export-bill') }}">
                        <i class="fas fa-file-excel mr-2"></i>{{ trans('messages.sidebar.export.bill') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('export-food') }}">
                        <i class="fas fa-file-excel mr-2"></i>{{ trans('messages.sidebar.export.food') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    @endif

    <!-- General Settings -->
    <ul class="list-unstyled components">
        <!-- Settings header -->
        <h4 class="text-uppercase">{{ trans('messages.sidebar.general_settings') }}</h4>

        <!-- Profile -->
        <li>
            <a href="#profileSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-id-badge mr-2"></i>{{ trans('messages.sidebar.profile.header') }}
            </a>
            <ul class="collapse list-unstyled" id="profileSidebar">
                <li>
                    <a href="{{ route('profile') }}">
                        <i class="fas fa-address-card mr-2"></i>{{ trans('messages.sidebar.profile.detail') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('change-password') }}">
                        <i class="fas fa-user-lock mr-2"></i>{{ trans('messages.sidebar.profile.change_password') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
