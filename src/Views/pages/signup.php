<div class="form-container w33">
    <h2>Créez votre compte IndéCiné</h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" value="<?= (isset($tempFields['first-name'])) ? $tempFields['first-name'] : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['first-name'])) ? $fieldsErrors['first-name'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" value="<?= (isset($tempFields['last-name'])) ? $tempFields['last-name'] : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['last-name'])) ? $fieldsErrors['last-name'] : null ?></p>
            </div>
        </div>

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

                <p class="field-error"><?= (isset($fieldsErrors['password'])) ? $fieldsErrors['password'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Créer un compte">
            </div>
        </div>
    </form>

    <p>Vous avez déjà un compte ? <a href="/signin">Connexion</a></p>
</div>