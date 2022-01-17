<?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
<div class="cards-container">
    <div class="card">
        <p class="title">TOTAL UTILISATEURS</p>
        <p class="value"><?= $totalUsers ?></p>
    </div>

    <div class="card">
        <p class="title">TOTAL RESERVATIONS</p>
        <p class="value"><?= $totalReservations ?></p>
    </div>

    <div class="card">
        <p class="title">TOTAL FILMS</p>
        <p class="value"><?= $totalMovies ?></p>
    </div>

    <div class="card">
        <p class="title">TOTAL ABONNES</p>
        <p class="value"><?= $totalUsers_Subscriptions ?></p>
    </div>
</div>
<?php endif; ?>

<h2>Bienvenue sur votre tableau de bord !</h2>