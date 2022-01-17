<div class="form-container hcenter w55">
    <h2><?= $screening -> movieTitle ?> - <?= format_date($screening -> screeningDate, 'display-slash') ?> en <?= $screening -> movieRoomName ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="movie">Film</label>
                <select name="movie" id="movie">
                    <option selected disabled>Sélectionnez un film</option>

                    <?php foreach($movies as $movie): ?>
                        <option value="<?= $movie -> movieId ?>" <?= ($screening -> movie_id == $movie -> movieId) ? 'selected' : null ?>><?= $movie -> movieTitle ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['movie'])) ? $fieldsErrors['movie'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="movie-room">Salle</label>
                <select name="movie-room" id="movie-room">
                    <option selected disabled>Sélectionnez une salle</option>

                    <?php foreach ($moviesRooms as $movieRoom): ?>
                        <option value="<?= $movieRoom -> movieRoomId ?>" <?= ($screening -> movieRoom_id == $movieRoom -> movieRoomId) ? 'selected' : null ?>><?= $movieRoom -> movieRoomName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['movie-room'])) ? $fieldsErrors['movie-room'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="date">Date</label>
                <input type="text" name="date" placeholder="jj-mm-aaaa" value="<?= format_date($screening -> screeningDate, 'display-dash') ?>">

                <p class="field-error"><?= (isset($fieldsErrors['date'])) ? $fieldsErrors['date'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="time">Horaire</label>
                <input type="text" name="time" placeholder="hh:mm:ss" value="<?= $screening -> screeningTime ?>">

                <p class="field-error"><?= (isset($fieldsErrors['time'])) ? $fieldsErrors['time'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div>