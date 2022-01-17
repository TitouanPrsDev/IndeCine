<div class="form-container hcenter w55">
    <h2><?= $subscription -> subscriptionName ?></h2>

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
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $subscription -> subscriptionId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" value="<?= $subscription -> subscriptionName ?>" readonly>
            </div>

            <div class="form-field w33">
                <label for="name">Tarif</label>
                <input type="text" name="pricing" id="pricing" value="<?= $subscription -> subscriptionPricing ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" placeholder="<?= ($subscription -> subscriptionDescription === null) ? 'Non renseignée' : null ?>" readonly><?= $subscription -> subscriptionDescription ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-red" type="submit" name="submit" value="Supprimer">
            </div>
        </div>
    </form>
</div>