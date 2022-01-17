<div class="form-container hcenter w55">
    <h2><?= $subscription -> subscriptionName ?></h2>

    <div class="form-group">
        <?php if ($_SESSION['user'] -> userRole === 'admin'): ?>
        <div class="form-field w15">
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="<?= $subscription -> subscriptionId ?>" readonly>
        </div>
        <?php endif; ?>

        <div class="form-field">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="<?= $subscription -> subscriptionName ?>" readonly>
        </div>

        <div class="form-field w33">
            <label for="pricing">Tarif</label>
            <input type="text" name="pricing" id="pricing" value="<?= $subscription -> subscriptionPricing ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="5" placeholder="<?= ($subscription -> subscriptionDescription === null) ? 'Non renseignée' : null ?>" readonly><?= $subscription -> subscriptionDescription ?></textarea>
        </div>
    </div>
</div>