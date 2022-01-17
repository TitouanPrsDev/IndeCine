<div class="form-container hcenter w55">
    <h2><?= $user -> userLastName . " " . $user -> userFirstName?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field w20">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $user -> userId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" value="<?= $user -> userLastName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['last-name'])) ? $fieldsErrors['last-name'] : null ?></p>
            </div>

            <div class="form-field">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" value="<?= $user -> userFirstName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['first-name'])) ? $fieldsErrors['first-name'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" value="<?= $user -> userEmail ?>">

                <p class="field-error"><?= (isset($fieldsErrors['email'])) ? $fieldsErrors['email'] : null ?></p>
            </div>

            <div class="form-field w33">
                <label for="phone-number">Numéro de téléphone</label>
                <input type="text" name="phone-number" id="phone-number" value="<?= $user -> userPhoneNumber ?>" placeholder="<?= ($user -> userPhoneNumber === null) ? "Non renseigné" : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['phone-number'])) ? $fieldsErrors['profile-picture'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" value="************************************************************" readonly>

                <p class="field-error"><?= (isset($fieldsErrors['password'])) ? $fieldsErrors['password'] : null ?></p>
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
                <input type="text" name="birth-date" id="birth-date" value="<?= $user -> userBirthDate ?>" placeholder="<?= ($user -> userBirthDate === null) ? "Non renseignée" : null ?>">

                <p class="field-error"><?= (isset($fieldsErrors['birth-date'])) ? $fieldsErrors['birth-date'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <div class="table-container no-footer">
                    <div class="table-header">
                        <p>Abonnements actifs</p>

                        <button class="bg bg-blue" name="add">
                            <img src="/public/images/icons/regular_add--v1.png">
                            Ajouter
                        </button>
                    </div>

                    <table>
                        <thead>
                        <tr>
                            <th>Abonnement</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($users_subscriptions)): ?>
                            <tr>
                                <td>Aucun abonnement</td>
                            </tr>

                            <?php else: ?>
                            <?php foreach ($users_subscriptions as $subscription): ?>
                            <tr>
                                <td><?= $subscription -> subscriptionName ?></td>
                            </tr>
                            <?php endforeach ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="role">Rôle</label>
                <select name="role" id="role">
                    <option disabled>Sélectionnez un rôle</option>

                    <option value="client" <?= ($user -> userRole === 'client') ? 'selected' : null ?>>Client</option>
                    <option value="admin" <?= ($user -> userRole === 'admin') ? 'selected' : null ?>>Administrateur</option>
                </select>

                <p class="field-error"><?= (isset($fieldsErrors['role'])) ? $fieldsErrors['role'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div>