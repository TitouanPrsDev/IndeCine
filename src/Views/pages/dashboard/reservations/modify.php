<div class="form-container hcenter w55">
    <h2>Réservation n°<?= $reservation -> reservationId ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $reservation -> reservationId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="user">Utilisateur</label>
                <select name="user" id="user">
                    <option disabled>Sélectionnez un réalisateur</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user -> userId ?>" <?= ($reservation -> user_id == $user -> userId) ? 'selected' : null ?>><?= $user -> userLastName ?> <?= $user -> userFirstName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['director'])) ? $fieldsErrors['director'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="screening">Séance</label>
                <select name="screening" id="screening">
                    <option disabled>Sélectionnez une séance</option>

                    <?php foreach ($screenings as $screening): ?>
                    <option value="<?= $screening -> screeningId ?>" <?= ($reservation -> screening_id == $screening -> screeningId) ? 'selected' : null ?>><?= $screening -> movieTitle ?> - <?= format_date($screening -> screeningDate, 'display-slash') ?> à <?= $screening -> screeningTime ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['screening'])) ? $fieldsErrors['screening'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="seat">Siège</label>
                <select name="seat" id="seat">
                    <option disabled>Séléctionnez un siège</option>

                    <?php foreach ($seats as $seat): ?>
                    <option value="<?= $seat -> seatId ?>"><?= $seat -> movieRoomName ?> - Place (<?= $seat -> seatXPosition ?>, <?= $seat -> seatYPosition ?>)</option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['seat'])) ? $fieldsErrors['seat'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <input class="bg-blue" type="submit" name="submit" value="Modifier">
        </div>
    </form>
</div>