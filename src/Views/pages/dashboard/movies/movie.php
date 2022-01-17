<div class="form-container hcenter w55">
    <h2><?= $movie -> movieTitle ?></h2>

    <div class="form-group">
        <div class="form-field w15">
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="<?= $movie -> movieId ?>" readonly>
        </div>

        <div class="form-field">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" value="<?= $movie -> movieTitle ?>" readonly>
        </div>

        <div class="form-field w33">
            <label for="duration">Durée</label>
            <input type="text" name="duration" id="duration" value="<?= $movie -> movieDuration ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="director">Réalisateur</label>
            <input type="text" name="director" id="director" value="<?= $movie -> directorName ?>" readonly>
        </div>

        <div class="form-field w33">
            <label for="release-date">Date de sortie</label>
            <input type="text" name="release-date" id="release-date" value="<?= format_date($movie -> movieReleaseDate, 'display-dash')?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="synopsis">Synopsis</label>
            <textarea name="synopsis" id="synopsis" rows="5" readonly><?= $movie -> movieSynopsis ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="poster">Affiche</label>
            <input type="text" name="poster" id="poster" value="<?= $movie -> moviePoster ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="classification">Classification</label>
            <input type="text" name="classification" id="classification" value="<?= movie_classification($movie -> movieClassification) ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="first-screening-date">Date de première projection</label>
            <input type="text" name="first-screening-date" id="first-screening-date" value="<?= format_date($movie -> movieFirstScreeningDate, 'display-dash') ?>" readonly>
        </div>

        <div class="form-field">
            <label for="last-screening-date">Date de dernière projection</label>
            <input type="text" name="last-screening-date" id="last-screening-date" value="<?= format_date($movie -> movieLastScreeningDate, 'display-dash') ?>" readonly>
        </div>
    </div>
</div>