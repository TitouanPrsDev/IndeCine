<div class="form-container hcenter w55">
    <h2><?= $director -> directorName ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $director -> directorId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" value="<?= $director -> directorName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['name'])) ? $fieldsErrors['name'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="picture">Photo</label>
                <input type="text" name="picture" id="picture" value="<?= $director -> directorPicture ?>">

                <p class="field-error"><?= (isset($fieldsErrors['picture'])) ? $fieldsErrors['picture'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div>