<!-- Vertical Nav -->
<div class="hk-menu">
    <!-- Brand -->
    <div class="menu-header">
        <span>
            <a class="navbar-brand" href="{{url('/')}}">
                <img class="brand-img img-fluid" src="{{asset('dist/img/logo.png')}}" alt="brand" />
                <!-- <img class="brand-img img-fluid" src="dist/img/Jampack.svg" alt="brand" /> -->
            </a>
            <button class="btn btn-icon btn-rounded btn-flush-dark flush-soft-hover navbar-toggle">
                <span class="icon">
                    <span class="svg-icon fs-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-bar-to-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="10" y1="12" x2="20" y2="12"></line>
                            <line x1="10" y1="12" x2="14" y2="16"></line>
                            <line x1="10" y1="12" x2="14" y2="8"></line>
                            <line x1="4" y1="4" x2="4" y2="20"></line>
                        </svg>
                    </span>
                </span>
            </button>
        </span>
    </div>
    <!-- /Brand -->

    <!-- Main Menu -->
    <div data-simplebar class="nicescroll-bar">
        <div class="menu-content-wrap">
            <div class="menu-group">
                <ul class="navbar-nav flex-column">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('home')}}">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-template" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="4" y="4" width="16" height="4" rx="1" />
                                        <rect x="4" y="12" width="6" height="8" rx="1" />
                                        <line x1="14" y1="12" x2="20" y2="12" />
                                        <line x1="14" y1="16" x2="20" y2="16" />
                                        <line x1="14" y1="20" x2="20" y2="20" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Dashboard</span>
                            <span class="badge badge-sm badge-soft-pink ms-auto">Hot</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu-gap"></div>
            <div class="menu-group">
                <div class="nav-header">
                    <span>Apps</span>
                </div>
                <ul class="navbar-nav flex-column">
                    @canany(['leads.index', 'leads.converted'])
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#leads_list">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-details" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 5h8" />
                                        <path d="M13 9h5" />
                                        <path d="M13 15h8" />
                                        <path d="M13 19h5" />
                                        <rect x="3" y="4" width="6" height="6" rx="1" />
                                        <rect x="3" y="14" width="6" height="6" rx="1" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Leads</span>
                        </a>
                        <ul id="leads_list" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('leads.create')}}"><span class="nav-link-text">Create New</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('leads.index')}}"><span class="nav-link-text">Pending</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('leads.converted')}}"><span class="nav-link-text">Converted</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcanany
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#quotes_list">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M9 15l2 2l4 -4" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Quotations</span>
                        </a>
                        <ul id="quotes_list" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('quotations.index')}}"><span class="nav-link-text">Pending</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('quotation.win')}}"><span class="nav-link-text">Won</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('quotation.listall')}}"><span class="nav-link-text">Lists All </span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @canany(['visitors.index', 'enquiries.index'])
                    <li class="nav-item d-none">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_scrumboard">
                            <span class="nav-icon-wrap position-relative">
                                <!-- <span class="badge badge-sm badge-primary badge-sm badge-pill position-top-end-overflow">3</span> -->
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-kanban" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="4" y1="4" x2="10" y2="4" />
                                        <line x1="14" y1="4" x2="20" y2="4" />
                                        <rect x="4" y="8" width="6" height="12" rx="2" />
                                        <rect x="14" y="8" width="6" height="6" rx="2" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Exhibitions</span>
                        </a>
                        <ul id="dash_scrumboard" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('visitors.index')}}"><span class="nav-link-text">Visitors</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('enquiries.index')}}"><span class="nav-link-text">Enquiries</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcanany


                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_scrumboard">
                            <span class="nav-icon-wrap position-relative">
                                <!-- <span class="badge badge-sm badge-primary badge-sm badge-pill position-top-end-overflow">3</span> -->
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-kanban" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <line x1="4" y1="4" x2="10" y2="4" />
                                        <line x1="14" y1="4" x2="20" y2="4" />
                                        <rect x="4" y="8" width="6" height="12" rx="2" />
                                        <rect x="14" y="8" width="6" height="6" rx="2" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Products</span>
                        </a>
                        <ul id="dash_scrumboard" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('products.index')}}"><span class="nav-link-text">List Product</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('products.create')}}"><span class="nav-link-text">Create Product</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('products.import')}}"><span class="nav-link-text">Bulk Import</span></a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#reports_list">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-code-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 12h-1v5h1"></path>
                                        <path d="M14 12h1v5h-1"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Reports</span>
                        </a>
                        <ul id="reports_list" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item  d-none">
                                        <a class="nav-link" href="{{route('reports.leads')}}"><span class="nav-link-text">Leads Report</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('reports.summarynumber')}}"><span class="nav-link-text">Summary & Numbers Report</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('reports.quotations')}}"><span class="nav-link-text">Quotations Report</span></a>
                                    </li>
                                    <li class="nav-item  d-none">
                                        <a class="nav-link" href="{{route('reports.winners')}}"><span class="nav-link-text">Win Standing Report</span></a>
                                    </li>
                                    <li class="nav-item  d-none">
                                        <a class="nav-link" href="{{route('reports.suppliers')}}"><span class="nav-link-text">Suppliers Report</span></a>
                                    </li>
                                    <li class="nav-item  d-none">
                                        <a class="nav-link" href="{{route('reports.probability')}}"><span class="nav-link-text">Probability Report</span></a>
                                    </li>
                                    <li class="nav-item  d-none">
                                        <a class="nav-link" href="{{route('reports.employees')}}"><span class="nav-link-text">Employee Performance Report</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('reports.customers')}}"><span class="nav-link-text">Customer Report</span></a>
                                    </li>
                                    @canany(['meetings.index'])
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('meetings.index')}}"><span class="nav-link-text">Meetings</span></a>
                                    </li>
                                    @endcanany
                                </ul>
                            </li>
                        </ul>
                    </li>

                    @canany(['orders.index', 'orders.completed'])
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_orders">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-digit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <rect x="9" y="12" width="3" height="5" rx="1"></rect>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                        <path d="M15 12v5"></path>
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Orders</span>
                        </a>
                        <ul id="dash_orders" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('orders.index')}}"><span class="nav-link-text">Pending Orders</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('orders.completed')}}"><span class="nav-link-text">Closed Orders</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('stock.index')}}"><span class="nav-link-text">Stock List</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcanany
                    @canany(['purchaserequisition.index'])
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_prs">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-digit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <rect x="9" y="12" width="3" height="5" rx="1"></rect>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                        <path d="M15 12v5"></path>
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Purchase Requisition</span>
                        </a>
                        <ul id="dash_prs" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('purchaserequisition.index')}}"><span class="nav-link-text">Pending PR</span></a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcanany
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_contact">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-notebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18" />
                                        <line x1="13" y1="8" x2="15" y2="8" />
                                        <line x1="13" y1="12" x2="15" y2="12" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Contacts</span>
                        </a>
                        <ul id="dash_contact" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    @can('employees.index')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('employees.index')}}"><span class="nav-link-text">Employees</span></a>
                                    </li>
                                    @endcan
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('customers.index')}}"><span class="nav-link-text">Customers</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    @canany(['categories.index', 'countries.index', 'regions.index', 'suppliers.index'])
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_invoice">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <rect x="4" y="4" width="6" height="5" rx="2" />
                                        <rect x="4" y="13" width="6" height="7" rx="2" />
                                        <rect x="14" y="4" width="6" height="16" rx="2" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">Additions</span>
                        </a>
                        <ul id="dash_invoice" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{route('categories.index')}}"><span class="nav-link-text">Category</span></a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('countries.index')}}"><span class="nav-link-text">Country</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('regions.index')}}"><span class="nav-link-text">Regions</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('suppliers.index')}}"><span class="nav-link-text">Supplier</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('divisions.index')}}"><span class="nav-link-text">Divisions</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('departments.index')}}"><span class="nav-link-text">Departments</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('leadStatus')}}"><span class="nav-link-text">Lead Status</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('quotationStatus')}}"><span class="nav-link-text">Quotation Status</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('currency.index')}}"><span class="nav-link-text">Currency</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('conversion.index')}}"><span class="nav-link-text">Currency Conversion</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('areas.index')}}"><span class="nav-link-text">Employee Areas</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('pages.edit', 'privacy-policy')}}"><span class="nav-link-text">Privacy Policy</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcanany

                    @canany(['roles.index', 'permissions.index'])
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#dash_file">
                            <span class="nav-icon-wrap">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        <path d="M16 11h6m-3 -3v6" />
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-link-text">User Controls</span>
                        </a>
                        <ul id="dash_file" class="nav flex-column collapse  nav-children">
                            <li class="nav-item">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('roles.index')}}"><span class="nav-link-text">Assign Roles</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('permissions.index')}}"><span class="nav-link-text">Permissions</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcanany
                </ul>
            </div>
            <div class="menu-gap"></div>


        </div>
    </div>
    <!-- /Main Menu -->
</div>
<div id="hk_menu_backdrop" class="hk-menu-backdrop"></div>
<!-- /Vertical Nav -->