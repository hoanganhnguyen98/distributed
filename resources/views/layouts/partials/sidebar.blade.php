<!-- Sidebar  -->
<nav id="sidebar" class="p-3 mb-2 bg-secondary text-white">
    <div class="sidebar-header components">
        <a href="{{ route('home') }}">
            <h4>{{ trans('messages.sidebar.header') }}</h4>
            <strong>NR</strong>
        </a>
    </div>

    <ul class="list-unstyled components">
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
                        <i class="fas fa-list-alt mr-2"></i>{{ trans('messages.sidebar.food.list') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <ul class="list-unstyled components">
        <li>
            <a href="#billSidebar" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-users-cog mr-2"></i>{{ trans('messages.sidebar.bill.header') }}
            </a>
            <ul class="collapse list-unstyled" id="billSidebar">
                <li>
                    <a href="{{ route('create-bill') }}">
                        <i class="fas fa-user-plus mr-2"></i>{{ trans('messages.sidebar.bill.create') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-users mr-2"></i>{{ trans('messages.sidebar.bill.list') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
