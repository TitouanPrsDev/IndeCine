<div class="form-container hcenter w55">
    <h2>Ajouter un utilisateur</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="message-container <?= (isset($_SESSION['message']['success'])) ? 'success' : (isset($_SESSION['message']['error']) ? 'error' : null) ?>">
            <?php if (isset($_SESSION['message']['success'])): ?>
                <p><?= $_SESSION['message']['success'] ?></p>
                <?php unset($_SESSION['message']['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message']['error'])): ?>
                <p><?= $_SESSION['message']['error'] ?></p>
                <?php unset($_SESSION['message']['error']); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="subscription">Abonnement</label>
                <select name="subscription" id="subscription">
                    <option selected disabled>Sélectionnez un abonnement</option>

                    <?php foreach ($subscriptions as $subscription): ?>
                    <option value="<?= $subscription -> subscriptionId ?>"><?= $subscription -> subscriptionName ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['subscription'])) ? $fieldsErrors['subscription'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Ajouter">
            </div>
        </div>
    </form>
</div>