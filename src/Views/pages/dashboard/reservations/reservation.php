<div class="form-container hcenter w55">
    <h2>Réservation n°<?= $reservation -> reservationId ?></h2>

    <div class="form-group">
        <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
        <div class="form-field w15">
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="<?= $reservation -> reservationId ?>" readonly>
        </div>
        <?php endif; ?>

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
</div>