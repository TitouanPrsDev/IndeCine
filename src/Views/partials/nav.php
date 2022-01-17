<nav>
    <a href="/dashboard" class="menu top <?= (!isset(explode('/', $_GET['p'])[1])) ? 'selected-menu' : null ?>">
        <img src="/public/images/icons/regular_home.png">
        <p>Tableau de bord</p>
    </a>

    <div class="menu">
        <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
        <p>Administration</p>

        <a href="/dashboard/movies" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'movies') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_cinema-.png">
            <p>Films</p>
        </a>

        <a href="/dashboard/screenings" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'screenings') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_ticket.png">
            <p>Séances</p>
        </a>

        <a href="/dashboard/reservations" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'reservations') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_train-ticket.png">
            <p>Réservations</p>
        </a>

        <a href="/dashboard/movies_rooms" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'movies_rooms') ? 'selected-menu' : null?>">
            <img src="/public/images/icons/regular_movie-theater.png">
            <p>Salles</p>
        </a>

        <a href="/dashboard/pricings" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'pricings') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_euro-pound-exchange.png">
            <p>Tarifs</p>
        </a>

        <a href="/dashboard/subscriptions" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'subscriptions') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_membership-card.png">
            <p>Abonnements</p>
        </a>

        <a href="/dashboard/users" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'users') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_user.png">
            <p>Utilisateurs</p>
        </a>

        <a href="/dashboard/directors" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'directors') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_clapperboard-2.png">
            <p>Réalisateurs</p>
        </a>

        <a href="/dashboard/profile" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'profile') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_user.png">
            <p>Profil</p>
        </a>

        <?php else: ?>
        <p>Navigation</p>

        <a href="/dashboard/profile" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'profile') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_user.png">
            <p>Profil</p>
        </a>

        <a href="/dashboard/reservations" class="submenu <?= (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'reservations') ? 'selected-menu' : null ?>">
            <img src="/public/images/icons/regular_train-ticket.png">
            <p>Réservations</p>
        </a>
        <a href="/dashboard/subscriptions" class="submenu <?php if (isset(explode('/', $_GET['p'])[1]) && explode('/', $_GET['p'])[1] === 'subscriptions') echo 'selected-menu'; ?>">
            <img src="/public/images/icons/regular_membership-card.png">
            <p>Abonnements</p>
        </a>
        <?php endif; ?>
    </div>
</nav>
