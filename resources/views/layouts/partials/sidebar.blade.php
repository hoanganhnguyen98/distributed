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

        <!-- ACCOUNT -->
        <li>
            <a href="#accountSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-users-cog mr-2"></i>{{ trans('messages.sidebar.account.header') }}
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

        <!-- FOOD -->
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

        <!-- BILL -->
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
</nav>
