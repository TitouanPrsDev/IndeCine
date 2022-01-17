<div class="form-container hcenter w55">
    <h2><?= $pricing -> pricingName ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $pricing -> pricingId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" value="<?= $pricing -> pricingName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['name'])) ? $fieldsErrors['name'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="pricing">Tarif</label>
                <input type="text" name="pricing" id="pricing" value="<?= $pricing -> pricing ?>">

                <p class="field-error"><?= (isset($fieldsErrors['pricing'])) ? $fieldsErrors['pricing'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" placeholder="<?= ($pricing -> pricingDescription === null) ? 'Non renseignée' : null ?>"><?= $pricing -> pricingDescription ?></textarea>

                <p class="field-error"><?= (isset($fieldsErrors['description'])) ? $fieldsErrors['description'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div>