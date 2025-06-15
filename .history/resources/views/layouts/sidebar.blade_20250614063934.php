<aside class="main-sidebar sidebar-dark-primary elevation-4">
       <a href="{{ url('/') }}" class="brand-link">
           <span class="brand-text font-weight-light">Starter Kit</span>
       </a>
       <div class="sidebar">
           <nav class="mt-2">
               <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                   <li class="nav-item">
                       <a href="{{ route('dashboard') }}" class="nav-link">
                           <i class="nav-icon fas fa-tachometer-alt"></i>
                           <p>Dashboard</p>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a href="{{ route('berita.index') }}" class="nav-link">
                           <i class="nav-icon fas fa-newspaper"></i>
                           <p>Berita</p>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a href="{{ route('profile.edit') }}" class="nav-link">
                           <i class="nav-icon fas fa-user"></i>
                           <p>Profil</p>
                       </a>
                   </li>
               </ul>
           </nav>
       </div>
</aside>