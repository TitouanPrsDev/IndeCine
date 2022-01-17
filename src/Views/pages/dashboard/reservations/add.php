<div class="form-container hcenter w55">
    <h2>Nouvelle réservation</h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="user">Utilisateur</label>
                <select name="user" id="user">
                    <option selected disabled>Sélectionnez un utilisateur</option>

                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user -> userId ?>"><?= $user -> userLastName ?> <?= $user -> userFirstName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['user'])) ? $fieldsErrors['user'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="screening">Séance</label>
                <select name="screening" id="screening">
                    <option selected disabled>Sélectionnez une séance</option>

                    <?php foreach ($screenings as $screening): ?>
                    <option value="<?= $screening -> screeningId ?>"><?= $screening -> movieTitle ?> - <?= format_date($screening -> screeningDate, 'display-slash') ?> à <?= $screening -> screeningTime ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['screening'])) ? $fieldsErrors['screening'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="date">Date</label>
                <input type="text" name="date" id="date" placeholder="jj-mm-aaaa">

                <p class="field-error"><?= (isset($fieldsErrors['date'])) ? $fieldsErrors['date'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="time">Horaire</label>
                <input type="text" name="time" id="time" placeholder="hh:mm:ss">

                <p class="field-error"><?= (isset($fieldsErrors['time'])) ? $fieldsErrors['time'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Ajouter">
            </div>
        </div>
    </form>
</div>