<div class="form-container hcenter w55">
    <h2>Profil</h2>

    <?php if (!empty($_SESSION['message']['success'])): ?>
    <div class="message-container <?= (isset($_SESSION['message']['success'])) ? 'success' : null ?>">
        <?php if (isset($_SESSION['message']['success'])): ?>
        <p><?= $_SESSION['message']['success'] ?></p>
        <?php unset($_SESSION['message']['success']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <div class="form-field">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" value="<?= $user -> userFirstName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['first-name'])) ? $fieldsErrors['first-name'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" value="<?= $user -> userLastName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['last-name'])) ? $fieldsErrors['last-name'] : null ?></p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-field">
                <label for="email">Adresse email</label>
                <input type="text" name="email" id="email" value="<?= $user -> userEmail ?>">

                <p class="field-error"><?= (isset($fieldsErrors['email'])) ? $fieldsErrors['email'] : null ?></p>
            </div>
            
            <div class="form-field w33">
                <label for="phone-number">
                    <label for="phone-number">Numéro de téléphone</label>
                    <input type="text" name="phone-number" id="phone-number" value="<?= $user -> userPhoneNumber ?>" placeholder="<?= ($user -> userPhoneNumber === null) ? "Non renseigné" : null ?>">

                    <p class="field-error"><?= (isset($fieldsErrors['phone-number'])) ? $fieldsErrors['phone-number'] : null ?></p>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="profile-picture">Photo de profil</label>
                <input type="text" name="profile-picture" id="profile-picture" value="<?= $user -> userProfilePicture ?>" placeholder="<?= ($user -> userProfilePicture === null) ? "Non renseignée" : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['profile-picture'])) ? $fieldsErrors['profile-picture'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="birth-date">Date de naissance</label>
                <input type="text" name="birth-date" id="birth-date" value="<?= $user -> userBirthDate ?>" placeholder="<?= ($user -> userBirthDate === null) ? "Non renseignée" : null ?>"">

                <p class="field-error"><?= (isset($fieldsErrors['birth-date'])) ? $fieldsErrors['birth-date'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">

                <p class="field-error"><?= (isset($fieldsErrors['last-name'])) ? $fieldsErrors['userLastName'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="role">Rôle</label>
                <input type="text" name="role" id="role" value="<?= ucfirst($user -> userRole) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div