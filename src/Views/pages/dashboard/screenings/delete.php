<div class="form-container hcenter w55">
    <h2><?= $screening -> movieTitle ?> - <?= format_date($screening -> screeningDate, 'display-slash') ?> en <?= $screening -> movieRoomName ?></h2>

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

        <div class="form-group">
            <div class="form-field">
                <input class="bg-red" type="submit" name="submit" value="Supprimer">
            </div>
        </div>
    </form>
</div>