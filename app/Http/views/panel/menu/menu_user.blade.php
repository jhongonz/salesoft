<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ URL::asset('theme/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
        <span class="hidden-xs"></span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <img src="{{ URL::asset('theme/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            <p>{{ $user->name }}</p>
        </li>
        <!-- Menu Body -->
        <!-- <li class="user-body">
        <div class="row">
        <div class="col-xs-4 text-center">
        <a href="#">Followers</a>
        </div>
        <div class="col-xs-4 text-center">
        <a href="#">Sales</a>
        </div>
        <div class="col-xs-4 text-center">
        <a href="#">Friends</a>
        </div>
        </div>
        </li> -->
        <!-- Menu Footer-->
        <li class="user-footer">
            <!-- <div class="pull-left">
                <a href="#" class="btn btn-default btn-sm btn-flat">Perfil</a>
            </div> -->
            <div class="pull-right">
                <a href="#" class="logout btn btn-default btn-sm btn-flat">Cerrar Sesion</a>
            </div>
        </li>
    </ul>
</li>