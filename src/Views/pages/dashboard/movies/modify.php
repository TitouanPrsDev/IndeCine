<div class="form-container hcenter w55">
    <h2><?= $movie -> movieTitle ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $movie -> movieId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="title">Titre</label>
                <input type="text" name="title" id="title" value="<?= $movie -> movieTitle ?>">

                <p class="field-error"><?= (isset($fieldsErrors['title'])) ? $fieldsErrors['title'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="duration">Durée</label>
                <input type="text" name="duration" id="duration" value="<?= $movie -> movieDuration ?>">

                <p class="field-error"><?= (isset($fieldsErrors['duration'])) ? $fieldsErrors['duration'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="director">Réalisateur</label>
                <select name="director" id="director">
                    <option disabled>Sélectionnez un réalisateur</option>

                    <?php foreach ($directors as $director): ?>
                    <option value="<?= $director -> directorId ?>" <?= ($movie -> director_id == $director -> directorId) ? 'selected' : null ?>><?= $director -> directorName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['director'])) ? $fieldsErrors['director'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="release-date">Date de sortie</label>
                <input type="text" name="release-date" id="release-date" value="<?= format_date($movie -> movieReleaseDate, 'display-dash')?>">

                <p class="field-error"><?= (isset($fieldsErrors['release-date'])) ? $fieldsErrors['release-date'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="synopsis">Synopsis</label>
                <textarea name="synopsis" id="synopsis" rows="5"><?= $movie -> movieSynopsis ?></textarea>

                <p class="field-error"><?= (isset($fieldsErrors['synopsis'])) ? $fieldsErrors['synopsis'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="poster">Affiche</label>
                <input type="text" name="poster" id="poster" value="<?= $movie -> moviePoster ?>">

                <p class="field-error"><?= (isset($fieldsErrors['poster'])) ? $fieldsErrors['poster'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="classification">Classification</label>
                <select name="classification" id="classification">
                    <option disabled>Sélectionnez une classification</option>
                    <option value="non-classe" <?= ($movie -> movieClassification === 'non-classe') ? "selected" : null ?>>Non classé</option>
                    <option value="tous-publics" <?= ($movie -> movieClassification === 'tous-publics') ? "selected" : null ?>>Tous publics</option>
                    <option value="moins-12" <?= ($movie -> movieClassification === 'moins-12') ? "selected" : null ?>>-12 ans</option>
                    <option value="moins-16" <?= ($movie -> movieClassification === 'moins-16') ? "selected" : null ?>>-16 ans</option>
                    <option value="moins-18" <?= ($movie -> movieClassification === 'moins-18') ? "selected" : null ?>>-18 ans</option>
                    <option value="moins-18-x" <?= ($movie -> movieClassification === 'moins-18-x') ? "selected" : null ?>>-18 ans (classé X)</option>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['classification'])) ? $fieldsErrors['classification'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="first-screening-date">Date de première projection</label>
                <input type="text" name="first-screening-date" id="first-screening-date" value="<?= format_date($movie -> movieFirstScreeningDate, 'display-dash') ?>">

                <p class="field-error"><?= (isset($fieldsErrors['first-screening-date'])) ? $fieldsErrors['first-screening-date'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="last-screening-date">Date de dernière projection</label>
                <input type="text" name="last-screening-date" id="last-screening-date" value="<?= format_date($movie -> movieLastScreeningDate, 'display-dash') ?>">

                <p class="field-error"><?= (isset($fieldsErrors['last-screening-date'])) ? $fieldsErrors['last-screening-date'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div>