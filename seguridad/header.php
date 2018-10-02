<nav class="navbar navbar-landing navbar-expand-lg navbar-dark bg-blue p-2">
    <div class="container">
        <a class="navbar-brand mr-auto" href="/"> <img class="logo" src="/static/images/logos/{{ logo }}">
            Sistema de Gestión</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa fa-calendar-check"></i>
                        Periodo Lectivo : Abr a Sep 2018
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="font-size: 13px">
                        <a class="dropdown-item active" href="#">
                            <i class="fa fa-check"></i>
                            REGULAR ABRIL A SEPTIEMBRE 2018: 23-04-2018 a 28-09-2018
                        </a>
                        <div class="dropdown-divider"></div>

                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user-circle"></i>
                        {{ usuario.last_name }} {{ usuario.first_name }} <span style="font-size: 12px">({{ grupo.name }})</span>
                    </a>
                    {% if user_grupos %}
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="font-size: 13px">
                        {% for g in user_grupos %}
                        {% if g.id == grupo.id %}
                        <a class="dropdown-item active" href="/session/grupos/?gpid={{ g.id }}">
                            <i class="fa fa-check"></i>
                            {{ usuario.last_name }} {{ usuario.first_name }} ({{ g.name }})
                        </a>
                        {% else %}
                        <a class="dropdown-item" href="/session/grupos/?gpid={{ g.id }}">
                            {{ usuario.last_name }} {{ usuario.first_name }} ({{ g.name }})
                        </a>

                        {% endif %}

                        {% endfor %}
                        <div class="dropdown-divider"></div>
                    </div>
                    {% endif %}

                </li>

                <li class="nav-item">
                    <a class="nav-link page-scroll text-white" href="/admin/password_change/" title="Cambiar Contraseña">
                        <i class="fa fa-lock"></i>
                    </a>
                </li>

                {% if usuario.is_staff %}
                <li class="nav-item">
                    <a class="nav-link page-scroll text-white" href="/admin/" title="Mantenimiento">
                        <i class="fa fa-cogs"></i>
                    </a>
                </li>
                {% endif %}

                <li class="nav-item">
                    <a class="nav-link page-scroll text-white" href="/salir/">
                        <i class="fa fa-sign-out-alt"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div> <!-- container //  -->
</nav>