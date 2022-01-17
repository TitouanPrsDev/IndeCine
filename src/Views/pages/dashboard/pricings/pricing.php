<div class="form-container hcenter w55">
    <h2><?= $pricing -> pricingName ?></h2>

    <div class="form-group">
        <div class="form-field w15">
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="<?= $pricing -> pricingId ?>" readonly>
        </div>

        <div class="form-field">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="<?= $pricing -> pricingName ?>" readonly>
        </div>

        <div class="form-field w33">
            <label for="pricing">Tarif</label>
            <input type="text" name="pricing" id="pricing" value="<?= $pricing -> pricing ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="5" placeholder="<?= ($pricing -> pricingDescription === null) ? 'Non renseignée' : null ?>" readonly><?= $pricing -> pricingDescription ?></textarea>
        </div>
    </div>
</div>