<ul class="navbar-nav iq-main-menu"  id="sidebar">
<li class="nav-item">
        <a class="nav-link" aria-current="page" href="/">
            <i class="icon">
            <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                            
                <path d="M9.13478 20.7733V17.7156C9.13478 16.9351 9.77217 16.3023 10.5584 16.3023H13.4326C13.8102 16.3023 14.1723 16.4512 14.4393 16.7163C14.7063 16.9813 14.8563 17.3408 14.8563 17.7156V20.7733C14.8539 21.0978 14.9821 21.4099 15.2124 21.6402C15.4427 21.8705 15.7561 22 16.0829 22H18.0438C18.9596 22.0023 19.8388 21.6428 20.4872 21.0008C21.1356 20.3588 21.5 19.487 21.5 18.5778V9.86686C21.5 9.13246 21.1721 8.43584 20.6046 7.96467L13.934 2.67587C12.7737 1.74856 11.1111 1.7785 9.98539 2.74698L3.46701 7.96467C2.87274 8.42195 2.51755 9.12064 2.5 9.86686V18.5689C2.5 20.4639 4.04738 22 5.95617 22H7.87229C8.55123 22 9.103 21.4562 9.10792 20.7822L9.13478 20.7733Z" fill="currentColor"></path>                            
            </svg>                        
            </i>
            <span class="item-name">Home</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{activeRoute(route('dashboard'))}}" aria-current="page" href="{{route('dashboard')}}">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"></path>
                </svg>
            </i>
            <span class="item-name">Dashboard</span>
        </a>
    </li>
    <li><hr class="hr-horizontal"></li>

    @if (auth()->check() && strtolower(auth()->user()->user_type) == "seller")
        <li class="nav-item static-item">
            <a class="nav-link static-item disabled" href="#" tabindex="-1">
                <span class="default-icon">Control Panel</span>
                <span class="mini-icon">-</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link " data-bs-toggle="collapse" href="#sidebar-post" role="button" aria-expanded="false" aria-controls="sidebar-fines">
                <i class="fa fa-shopping-basket" style = "margin-top: 4px"></i>
                <span class="item-name">Products</span>
                <i class="right-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </i>
            </a>
            <ul class="sub-nav collapse" id="sidebar-post" data-bs-parent="#sidebar">
                
                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('post.create'))}}" href="{{route('post.create')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> NA </i>
                        <span class="item-name">New</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('post.index'))}}" href="{{route('post.index')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> MP </i>
                        <span class="item-name">My Posts</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('post.drafts'))}}" href="{{route('post.drafts')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> DR </i>
                        <span class="item-name">Drafts</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link " data-bs-toggle="collapse" href="#sidebar-order" role="button" aria-expanded="false" aria-controls="sidebar-order">
                <i class="fa fa-shopping-cart" style = "margin-top: 4px"></i>
                <span class="item-name">Orders</span>
                <i class="right-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </i>
            </a>
            <ul class="sub-nav collapse" id="sidebar-order" data-bs-parent="#sidebar">

                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('order.list'))}}" href="{{route('order.list')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> LI </i>
                        <span class="item-name">For Confirmation</span>
                    </a>
                </li>
            </ul>
        </li>

    @else
        <li class="nav-item">
            <a class="nav-link " data-bs-toggle="collapse" href="#sidebar-order" role="button" aria-expanded="false" aria-controls="sidebar-order">
                <i class="fa fa-shopping-cart" style = "margin-top: 4px"></i>
                <span class="item-name">Orders</span>
                <i class="right-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </i>
            </a>
            <ul class="sub-nav collapse" id="sidebar-order" data-bs-parent="#sidebar">

                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('order.view'))}}" href="{{route('order.view')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> MO </i>
                        <span class="item-name">My Orders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('order.myconfirm'))}}" href="{{route('order.myconfirm')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> CO </i>
                        <span class="item-name">My Confirmed Orders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{activeRoute(route('cancel.buyer'))}}" href="{{route('cancel.buyer')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                <g>
                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                </g>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> CO </i>
                        <span class="item-name">My Cancellations</span>
                    </a>
                </li>

            </ul>
        </li>
    @endif
</ul>
