<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <div class="header-left">
                <a href="{{ route('admin.dashboard') }}" class="logo d-flex justify-content-center">
                    <img src="{{ asset(setting('primary_logo')) }}" class="my-2" alt="{{ setting('app_name') }}"
                        width="70%" style="height:auto">
                </a>
            </div>

            <ul class="sidebar-ul">
                {{-- Dashboard --}}
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a class="d-flex align-items-center border-top-1" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door-fill" style="width: 18px; font-size:16px;"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Sales Management --}}
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}"
                        href="javascript:;">
                        <i class="bi bi-cart-fill" style="width: 18px; font-size:16px;"></i>
                        <span>Sales Management</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.groupticket*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.groupticket.index') }}">
                                <i class="bi bi-people-fill me-2"></i><span>Group Ticket</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.singleticket*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.singleticket.index') }}">
                                <i class="bi bi-ticket-fill me-2"></i><span>Air Ticket</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.refundticket*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.refundticket.index') }}">
                                <i class="bi bi-arrow-counterclockwise me-2"></i><span>Refund Ticket</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.hotel*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.hotel.index') }}">
                                <i class="bi bi-building me-2"></i><span>Hotel Booking</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.manpower*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.manpower.index') }}">
                                <i class="bi bi-people-fill me-2"></i><span>Manpower</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.passport*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.passport.index') }}">
                                <i class="bi bi-pass me-2"></i><span>Passport</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.visasale*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.visasale.index') }}">
                                <i class="bi bi-file-earmark-text-fill me-2"></i><span>Visa</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.inventory.other*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory.other.index') }}">
                                <i class="bi bi-box-seam me-2"></i><span>Others Bill</span>
                            </a>
                        </li>

                    </ul>
                </li>
                {{-- Customers Management --}}
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.customer*') ? 'active' : '' }}"
                        href="javascript:;">
                        <i class="bi bi-people-fill text-info" style="width: 18px; font-size:16px;"></i>
                        <span>Customers Management</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request('type') == 'due_customer' ? 'active' : '' }}"
                                href="{{ route('admin.customer.index', ['type' => 'due_customer']) }}">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                <span>Due Customers</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request('type') == 'due_our_agency' ? 'active' : '' }}"
                                href="{{ route('admin.customer.index', ['type' => 'due_our_agency']) }}">
                                <i class="bi bi-building-fill text-danger me-2"></i>
                                <span>Due Our Agency</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request('type') == 'all_customer' ? 'active' : '' }}"
                                href="{{ route('admin.customer.index', ['type' => 'all_customer']) }}">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                <span>All Customers</span>
                            </a>
                        </li>
                    </ul>
                </li>



                {{-- Accounts Management --}}
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}"
                        href="javascript:void(0);">
                        <i class="bi bi-wallet2" style="width:18px; font-size:16px;"></i>
                        <span class="ms-2">Accounts Management</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request()->routeIs('admin.accounts.index') ? 'active' : '' }}"
                                href="{{ route('admin.accounts.index') }}">
                                <i class="bi bi-list-ul me-2"></i><span>All Accounts</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request('type') === 'deposit' ? 'active' : '' }}"
                                href="{{ route('admin.accounts.transaction.index', ['type' => 'deposit']) }}">
                                <i class="bi bi-plus-circle-fill text-success me-2"></i><span>Deposit Accounts</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request('type') === 'withdraw' ? 'active' : '' }}"
                                href="{{ route('admin.accounts.transaction.index', ['type' => 'withdraw']) }}">
                                <i class="bi bi-dash-circle-fill text-danger me-2"></i><span>Withdraw Accounts</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request('type') === 'transfer' ? 'active' : '' }}"
                                href="{{ route('admin.accounts.transaction.index', ['type' => 'transfer']) }}">
                                <i class="bi bi-arrow-left-right text-warning me-2"></i><span>Balance Transfer</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Report Management --}}
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.report*') ? 'active' : '' }}"
                        href="javascript:;">
                        <i class="bi bi-bar-chart-fill" style="width:18px; font-size:16px;"></i>
                        <span>Report Management</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request()->routeIs('admin.report.saleReport') ? 'active' : '' }}"
                                href="{{ route('admin.report.saleReport') }}">
                                <i class="bi bi-arrow-down-circle-fill me-2"></i>
                                <span>Sale/Purchase Report</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.report.transaction') ? 'active' : '' }}"
                                href="{{ route('admin.report.transaction') }}">
                                <i class="bi bi-arrow-down-circle-fill me-2"></i>
                                <span>Accounts Report</span>
                            </a>
                        </li>

                        <li>
                            <a class="{{ request()->routeIs('admin.report.expenseReport') ? 'active' : '' }}"
                                href="{{ route('admin.report.expenseReport') }}">
                                <i class="bi bi-arrow-left-right me-2"></i>
                                <span>Expense Report</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.report.profitloss') ? 'active' : '' }}"
                                href="{{ route('admin.report.profitloss') }}">
                                <i class="bi bi-arrow-left-right me-2"></i>
                                <span>P & L Report</span>
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- Extra Income --}}
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.income*') ? 'active' : '' }}"
                        href="javascript:;">
                        <i class="bi bi-graph-up-arrow text-success" style="width:18px; font-size:16px;"></i>
                        <span>Other Income</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request()->routeIs('admin.income.category.index') ? 'active' : '' }}"
                                href="{{ route('admin.income.category.index') }}">
                                <i class="bi bi-tags-fill me-2"></i>
                                <span>Category</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.income.index') ? 'active' : '' }}"
                                href="{{ route('admin.income.index') }}">
                                <i class="bi bi-cash-coin text-success me-2"></i>
                                <span>Income Accounts</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Expense --}}
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.expense*') ? 'active' : '' }}"
                        href="javascript:;">
                        <i class="bi bi-receipt-cutoff text-danger" style="width:18px; font-size:16px;"></i>
                        <span>Expense</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request()->routeIs('admin.expense.category.index') ? 'active' : '' }}"
                                href="{{ route('admin.expense.category.index') }}">
                                <i class="bi bi-tags-fill me-2"></i>
                                <span>Category</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.expense.index') ? 'active' : '' }}"
                                href="{{ route('admin.expense.index') }}">
                                <i class="bi bi-wallet2 text-danger me-2"></i>
                                <span>Expenses</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('admin.note*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center border-top-1" href="{{ route('admin.note.index') }}">
                        <i class="bi bi-stickies" style="width: 18px; font-size:16px;"></i>
                        <span>Office Note</span>
                    </a>
                </li>


                <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center border-top-1" href="{{ route('admin.users.index') }}">
                        <i class="bi-shield-lock" style="width:18px; font-size:16px;"></i>
                        <span>System Users</span>
                    </a>
                </li>
                <li class="submenu">
                    <a class="d-flex align-items-center {{ request()->routeIs('admin.setting*') ? 'active' : '' }}"
                        href="javascript:;">
                        <i class="bi bi-gear-fill" style="width:18px; font-size:16px;"></i>
                        <span>Settings</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled" style="display: none;">
                        <li>
                            <a class="{{ request()->routeIs('admin.setting.general') ? 'active' : '' }}"
                                href="{{ route('admin.setting.general') }}">
                                <i class="bi bi-list-ul me-2"></i><span>General Setting</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->routeIs('admin.setting.information') ? 'active' : '' }}"
                                href="{{ route('admin.setting.information') }}">
                                <i class="bi bi-list-ul me-2"></i><span>Information Setting</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @role('admin')
                    <li class="{{ request()->routeIs('admin.cacheClear') ? 'active' : '' }}">
                        <a class="d-flex align-items-center border-top-1" href="{{ route('admin.cacheClear') }}">
                            <i class="bi bi-x-circle" style="width:18px; font-size:16px;"></i>
                            <span>Clear Cache</span>
                        </a>
                    </li>
                @endrole
            </ul>
        </div>
    </div>
</div>
