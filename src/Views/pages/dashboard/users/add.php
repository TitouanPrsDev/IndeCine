<div class="form-container hcenter w55">
    <h2>Ajouter un utilisateur</h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name">

                <p class="field-error"><?= (isset($fieldsErrors['last-name'])) ? $fieldsErrors['last-name'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name">

                <p class="field-error"><?= (isset($fieldsErrors['first-name'])) ? $fieldsErrors['first-name'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email">

                <p class="field-error"><?= (isset($fieldsErrors['email'])) ? $fieldsErrors['email'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="phone-number">Numéro de téléphone</label>
                <input type="text" name="phone-number" id="phone-number">

                <p class="field-error"><?= (isset($fieldsErrors['phone-number'])) ? $fieldsErrors['phone-number'] : null ?></p>
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
                <label for="profile-picture">Photo de profil</label>
                <input type="text" name="profile-picture" id="profile-picture" placeholder="URL de l'image">

                <p class="field-error"><?= (isset($fieldsErrors['profile-picture'])) ? $fieldsErrors['profile-picture'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="birth-date">Date de naissance</label>
                <input type="text" name="birth-date" id="birth-date" placeholder="jj-mm-aaaa">

                <p class="field-error"><?= (isset($fieldsErrors['birth-date'])) ? $fieldsErrors['birth-date'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="role">Rôle</label>
                <select name="role" id="role">
                    <option selected disabled>Sélectionnez un rôle</option>

                    <option value="client">Client</option>
                    <option value="admin">Administrateur</option>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['role'])) ? $fieldsErrors['role'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Ajouter">
            </div>
        </div>
    </form>
</div>