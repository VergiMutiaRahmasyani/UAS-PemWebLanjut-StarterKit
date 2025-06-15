<nav class="main-header navbar navbar-expand navbar-white navbar-light">
       <ul class="navbar-nav">
           <li class="nav-item">
               <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
           </li>
       </ul>
       <ul class="navbar-nav ml-auto">
           @auth
           <li class="nav-item">
               <a class="nav-link" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Logout
               </a>
               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                   @csrf
               </form>
           </li>
           @endauth
           @guest
           <li class="nav-item">
               <a class="nav-link" href="{{ route('login') }}">Login</a>
           </li>
           <li class="nav-item">
               <a class="nav-link" href="{{ route('register') }}">Register</a>
           </li>
           @endguest
       </ul>
   </nav>