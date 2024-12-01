<div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

                <div class="navbar-nav theme-brand flex-row  text-center">
                    <div class="nav-logo">
                        <div class="nav-item theme-logo">
                            <a href="./index.html">
                                <img src="{{asset('assets/src/assets/img/logo.svg')}}" class="navbar-logo" alt="logo">
                            </a>
                        </div>
                        <div class="nav-item theme-text">
                            <a href="./index.html" class="nav-link"> CORK </a>
                        </div>
                    </div>
                    <div class="nav-item sidebar-toggle">
                        <div class="btn-toggle sidebarCollapse">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                        </div>
                    </div>
                </div>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">

                    <li class="menu active">
                        <a href="{{route('admin.home')}}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{route('admin.teacher.index')}}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span>Teachers</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{route('admin.student.index')}}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span>Students</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{route('admin.batch.index')}}" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span>Batches</span>
                            </div>
                        </a>
                    </li>
                    {{-- <li class="menu">
                        <a href="#apps" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                                <span>Apps</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="apps" data-bs-parent="#accordionExample">
                            <li>
                                <a href="./app-calendar.html"> Calendar </a>
                            </li>
                            <li>
                                <a href="./app-chat.html"> Chat </a>
                            </li>
                            <li>
                                <a href="./app-mailbox.html"> Mailbox </a>
                            </li>
                            <li>
                                <a href="./app-todoList.html"> Todo List </a>
                            </li>
                            <li>
                                <a href="./app-notes.html"> Notes </a>
                            </li>
                            <li>
                                <a href="./app-scrumboard.html"> Scrumboard </a>
                            </li>
                            <li>
                                <a href="./app-contacts.html"> Contacts </a>
                            </li>
                            <li>
                                <a href="#invoice" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="invoice" data-bs-parent="#apps"> 
                                    <li>
                                        <a href="./app-invoice-list.html"> List </a>
                                    </li>
                                    <li>
                                        <a href="./app-invoice-preview.html"> Preview </a>
                                    </li>
                                    <li>
                                        <a href="./app-invoice-add.html"> Add </a>
                                    </li>
                                    <li>
                                        <a href="./app-invoice-edit.html"> Edit </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#ecommerce" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">Ecommerce <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="ecommerce" data-bs-parent="#apps"> 
                                    <li>
                                        <a href="./app-ecommerce-product-shop.html"> Shop </a>
                                    </li>
                                    <li>
                                        <a href="./app-ecommerce-product.html"> Product </a>
                                    </li>
                                    <li>
                                        <a href="./app-ecommerce-product-list.html"> List </a>
                                    </li>
                                    <li>
                                        <a href="./app-ecommerce-product-add.html"> Create </a>
                                    </li>                            
                                    <li>
                                        <a href="./app-ecommerce-product-edit.html"> Edit </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#blog" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">Blog <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="blog" data-bs-parent="#apps"> 
                                    <li>
                                        <a href="./app-blog-grid.html"> Grid </a>
                                    </li>
                                    <li>
                                        <a href="./app-blog-list.html"> List </a>
                                    </li>
                                    <li>
                                        <a href="./app-blog-post.html"> Post </a>
                                    </li>
                                    <li>
                                        <a href="./app-blog-create.html"> Create </a>
                                    </li>                            
                                    <li>
                                        <a href="./app-blog-edit.html"> Edit </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li> --}}
                    
                </ul>
                
            </nav>

        </div>