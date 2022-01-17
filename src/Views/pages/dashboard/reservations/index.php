<?php if (!empty($_SESSION['message'])): ?>
    <div class="message-container <?= (isset($_SESSION['message']['success'])) ? 'success' : (isset($_SESSION['message']['error']) ? 'error' : null) ?>">
        <?php if (isset($_SESSION['message']['success'])): ?>
            <p><?= $_SESSION['message']['success'] ?></p>
            <?php unset($_SESSION['message']['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['message']['error'])): ?>
            <p><?= $_SESSION['message']['error'] ?></p>
            <?php unset($_SESSION['message']['error']); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>


<div class="cards-container">
    <div class="card">
        <p class="title">TOTAL RÉSERVATIONS</p>
        <p class="value"><?= $totalReservations ?></p>
    </div>

    <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
    <div class="card">
        <p class="title">RÉSERVATIONS EN ATTENTE</p>
        <p class="value"><?= $waitingReservations ?></p>
    </div>

    <div class="card">
        <p class="title">RÉSERVATIONS TERMINEES</p>
        <p class="value"><?= $completedReservations ?></p>
    </div>
    <?php endif; ?>
</div>

<div class="table-container">
    <div class="table-header">
        <p>Réservations</p>

        <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
        <form method="post">
            <button class="bg bg-blue" name="add">
                <img src="/public/images/icons/regular_add--v1.png">
                Ajouter
            </button>
        </form>
        <?php endif; ?>
    </div>

    <form method="post">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date</th>
                    <th>Horaire</th>
                    <th>Tarif</th>
                    <th>Salle</th>
                    <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
                    <th></th>
                    <?php endif; ?>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                <tr onclick="window.location.href = '/dashboard/reservations/<?= $reservation -> reservationId ?>'">
                    <td><input type="checkbox"></td>
                    <td><?= $reservation -> userLastName ?></td>
                    <td><?= $reservation -> userFirstName ?></td>
                    <td><?= format_date($reservation -> screeningDate, 'display-slash') ?></td>
                    <td><?= $reservation -> screeningTime ?></td>
                    <td><?= $reservation -> pricing ?>€</td>
                    <td><?= $reservation -> movieRoomName ?></td>
                    <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
                    <td><button class="border" name="modify" value="<?= $reservation -> reservationId ?>">
                            <img src="/public/images/icons/regular_edit--v1.png">
                            Modifier
                        </button></td>
                    <?php endif; ?>
                    <td><button class="bg bg-red" name="delete" value="<?= $reservation -> reservationId ?>">
                            <img class="img-only" src="/public/images/icons/regular_filled-trash.png">
                        </button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>

    <div class="table-footer">
        <div class="lines-number">
            <p>Afficher :</p>

            <form method="post">
                <select name="show" onchange="onSelectSubmit()">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
            </form>

            <p>sur <?= $totalReservations ?></p>
        </div>

        <form class="change-page" method="post">
            <?php for ($i = 1; $i < $nbPages + 1; $i++): ?>
            <button class="border" name="page" value="<?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
        </form>
    </div>
</div>