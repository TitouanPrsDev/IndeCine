<div class="form-container hcenter w55">
    <h2>Nouveau réalisateur</h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name">

                <p class="field-error"><?= (isset($fieldsErrors['name'])) ? $fieldsErrors['name'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="picture">Photo</label>
                <input type="text" name="picture" id="picture">

                <p class="field-error"><?= (isset($fieldsErrors['picture'])) ? $fieldsErrors['picture'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Ajouter">
            </div>
        </div>
    </form>
</div>

