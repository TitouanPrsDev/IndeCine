<div class="form-container w33">
    <h2>Connectez-vous à votre compte</h2>

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
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" value="<?= (isset($tempFields['email'])) ? $tempFields['email'] : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['email'])) ? $fieldsErrors['email'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Connexion">
            </div>
        </div>
    </form>

    <p>Vous n'avez pas de compte ? <a href="/signup">Inscription</a></p>
</div>