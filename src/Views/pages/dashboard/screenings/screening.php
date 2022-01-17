<div class="form-container hcenter w55">
    <h2><?= $screening -> movieTitle ?> - <?= format_date($screening -> screeningDate, 'display-slash') ?> en <?= $screening -> movieRoomName ?></h2>

    <div class="form-group">
        <div class="form-field">
            <label for="movie">Film</label>
            <input type="text" name="movie" id="movie" value="<?= $screening -> movieTitle ?>" readonly>
        </div>

        <div class="form-field">
            <label for="movie-room">Salle</label>
            <input type="text" name="movie-room" id="movie-room" value="<?= $screening -> movieRoomName ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="date">Date</label>
            <input type="text" placeholder="jj-mm-aaaa" value="<?= $screening -> screeningDate ?>" readonly>
        </div>

        <div class="form-field">
            <label for="time">Horaire</label>
            <input type="text" placeholder="hh:mm:ss" value="<?= $screening -> screeningTime ?>" readonly>
        </div>
    </div>
</div>