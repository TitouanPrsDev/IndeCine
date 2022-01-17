<div class="form-container hcenter w55">
    <h2>Nouvelle séance</h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="movie">Film</label>
                <select name="movie" id="movie">
                    <option selected disabled>Sélectionnez un film</option>

                    <?php foreach($movies as $movie): ?>
                    <option value="<?= $movie -> movieId ?>"><?= $movie -> movieTitle ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['movie'])) ? $fieldsErrors['movie'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="movie-room">Salle</label>
                <select name="movie-room" id="movie-room">
                    <option selected disabled>Sélectionnez une salle</option>

                    <?php foreach ($moviesRooms as $movieRoom): ?>
                    <option value="<?= $movieRoom -> movieRoomId ?>"><?= $movieRoom -> movieRoomName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['movie-room'])) ? $fieldsErrors['movie-room'] : null ?></p>
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
                <input type="text" name="time" id="time" placeholder="hh:mm">

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