<div class="form-container hcenter w55">
    <h2>Réservation n°<?= $reservation -> reservationId ?></h2>

    <?php if (!empty($_SESSION['message']['error'])): ?>
    <div class="message-container <?= (isset($_SESSION['message']['error'])) ? 'error' : null ?>">
        <?php if (isset($_SESSION['message']['error'])): ?>
        <p><?= $_SESSION['message']['error'] ?></p>
        <?php unset($_SESSION['message']['error']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $reservation -> reservationId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="user">Utilisateur</label>
                <input type="text" name="user" id="user" value="<?= $reservation -> userLastName ?> <?= $reservation -> userFirstName ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="screening">Séance</label>
                <input type="text" name="screening" id="screening" value="<?= $reservation -> movieTitle ?> - <?= format_date($reservation -> screeningDate, 'display-slash') ?> à <?= $reservation -> screeningTime ?>" readonly>
            </div>

            <div class="form-field">
                <label for="seat">Siège</label>
                <input type="text" name="seat" id="seat" value="<?= $reservation -> movieRoomName ?> - Place (<?= $reservation -> seatXPosition ?>, <?= $reservation -> seatYPosition ?>)" readonly>
            </div>
        </div>

        <div class="form-group">
            <input class="bg-red" type="submit" name="submit" value="Supprimer">
        </div>
    </form>
</div>