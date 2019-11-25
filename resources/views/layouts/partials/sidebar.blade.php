<!-- Sidebar  -->
<nav id="sidebar" class="p-3 mb-2 bg-secondary text-white">
    <div class="sidebar-header components">
        <a href="{{ route('home') }}">
            <h4 class="text-uppercase">{{ trans('messages.sidebar.header') }}</h4>
            <strong>NR</strong>
        </a>
    </div>

    <!-- Managemnet -->
    <ul class="list-unstyled components">
        <!-- Managemnet header -->
        <h4 class="text-uppercase">{{ trans('messages.sidebar.management-header') }}</h4>

        <!-- Account -->
        <li>
            <a href="#accountSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-user-shield mr-2"></i>{{ trans('messages.sidebar.account.header') }}
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

        <!-- Bill -->
        <li>
            <a href="#billSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-file-alt mr-2"></i>{{ trans('messages.sidebar.bill.header') }}
            </a>
            <ul class="collapse list-unstyled" id="billSidebar">
                <li>
                    <a href="{{ route('create-bill') }}">
                        <i class="fas fa-file-signature mr-2"></i>{{ trans('messages.sidebar.bill.create') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-copy mr-2"></i>{{ trans('messages.sidebar.bill.list') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <!-- General Settings -->
    <ul class="list-unstyled components">
        <!-- Settings header -->
        <h4 class="text-uppercase">{{ trans('messages.sidebar.general-settings') }}</h4>

        <!-- Profile -->
        <li>
            <a href="#profileSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-id-badge mr-2"></i>{{ trans('messages.sidebar.profile.header') }}
            </a>
            <ul class="collapse list-unstyled" id="profileSidebar">
                <li>
                    <a href="#">
                        <i class="fas fa-address-card mr-2"></i>{{ trans('messages.sidebar.profile.detail') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('change-password') }}">
                        <i class="fas fa-user-lock mr-2"></i>{{ trans('messages.sidebar.profile.change-password') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
