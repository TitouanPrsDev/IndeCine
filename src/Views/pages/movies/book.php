<div class="form-container hcenter w33">
    <h2>Réserver - <?= $screening -> movieTitle ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="screening-date">Date</label>
                <input type="text" name="screening-date" id="screening-date" value="<?= format_date($screening -> screeningDate, 'display-slash') ?>" readonly>
            </div>

            <div class="form-field">
                <label for="screening-time">Horaire</label>
                <input type="text" name="screening-time" id="screening-time" value="<?= $screening -> screeningTime ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="movie-room">Salle</label>
                <input type="text" name="movie-room" id="movie-room" value="<?= $screening -> movieRoomName ?>" readonly>
            </div>

            <div class="form-field">
                <label for="seat">Place</label>

                <select name="seat" id="seat">
                    <option selected disabled>Sélectionnez une place</option>

                    <?php foreach ($seats as $seat): ?>
                        <option value="<?= $seat -> seatId ?>"><?= $seat -> movieRoomName ?> - Place (<?= $seat -> seatXPosition ?>, <?= $seat -> seatYPosition ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="pricing">Tarif</label>
                <select name="pricing" id="pricing">
                    <option selected disabled>Sélectionnez un tarif</option>

                    <?php foreach ($pricings as $pricing): ?>
                        <option value="<?= $pricing -> pricingId ?>"><?= $pricing -> pricingName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg bg-blue" type="submit" name="submit" value="Valider la réservation">
            </div>
        </div>
    </form>
</div>