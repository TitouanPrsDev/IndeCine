<div class="form-container hcenter w55">
    <h2>Nouveau film</h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="title">Titre</label>
                <input type="text" name="title" id="title" value="<?= (isset($tempFields['title'])) ? $tempFields['title'] : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['title'])) ? $fieldsErrors['title'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="duration">Durée</label>
                <input type="text" name="duration" id="duration" value="<?= (isset($tempFields['duration'])) ? $tempFields['duration'] : null ?>" placeholder="000">

                <p class="field-error"><?= (isset($fieldsErrors['duration'])) ? $fieldsErrors['duration'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="director">Réalisateur</label>
                <select name="director" id="director">
                    <option selected disabled>Sélectionnez un réalisateur</option>

                    <?php foreach ($directors as $director): ?>
                    <option value="<?= $director -> directorId ?>"><?= $director -> directorName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['director'])) ? $fieldsErrors['director'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="release-date">Date de sortie</label>
                <input type="text" name="release-date" id="release-date" value="<?= (isset($tempFields['release-date'])) ? $tempFields['release-date'] : null ?>" placeholder="jj-mm-aaaa">

                <p class="field-error"><?= (isset($fieldsErrors['release-date'])) ? $fieldsErrors['release-date'] : null ?></p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-field">
                <label for="synopsis">Synopsis</label>
                <textarea name="synopsis" id="synopsis" rows="3"><?= (isset($tempFields['synopsis'])) ? $tempFields['synopsis'] : null ?></textarea>

                <p class="field-error"><?= (isset($fieldsErrors['synopsis'])) ? $fieldsErrors['synopsis'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="poster">Affiche</label>
                <input type="text" name="poster" id="poster" value="<?= (isset($tempFields['poster'])) ? $tempFields['poster'] : null ?>" placeholder="URL de l'image">

                <p class="field-error"><?= (isset($fieldsErrors['poster'])) ? $fieldsErrors['poster'] : null ?></p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-field">
                <label for="classification">Classification</label>
                <select name="classification" id="classification">
                    <option selected disabled>Sélectionnez une classification</option>
                    <option value="non-classe">Non classé</option>
                    <option value="tous-publics">Tous publics</option>
                    <option value="moins-12">-12 ans</option>
                    <option value="moins-16">-16 ans</option>
                    <option value="moins-18">-18 ans</option>
                    <option value="moins-18-x">-18 ans (classé X)</option>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['classification'])) ? $fieldsErrors['classification'] : null ?></p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-field">
                <label for="first-screening-date">Date de première projection</label>
                <input type="text" name="first-screening-date" id="first-screening-date" value="<?= (isset($tempFields['first-screening-date'])) ? $tempFields['first-screening-date'] : null ?>" placeholder="jj-mm-aaaa">

                <p class="field-error"><?= (isset($fieldsErrors['first-screening-date'])) ? $fieldsErrors['first-screening-date'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="last-screening-date">Date de dernière projection</label>
                <input type="text" name="last-screening-date" id="last-screening-date" value="<?= (isset($tempFields['last-screening-date'])) ? $tempFields['last-screening-date'] : null ?>" placeholder="jj-mm-aaaa">

                <p class="field-error"><?= (isset($fieldsErrors['last-screening-date'])) ? $fieldsErrors['last-screening-date'] : null?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Ajouter">
            </div>
        </div>
    </form>
</div>

