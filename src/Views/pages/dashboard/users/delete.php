<div class="form-container hcenter w55">
    <h2><?= $user -> userLastName . " " . $user -> userFirstName?></h2>

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
            <div class="form-field w20">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $user -> userId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" value="<?= $user -> userLastName ?>" readonly>
            </div>

            <div class="form-field">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" value="<?= $user -> userFirstName ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" value="<?= $user -> userEmail ?>" readonly>
            </div>

            <div class="form-field w33">
                <label for="phone-number">Numéro de téléphone</label>
                <input type="text" name="phone-number" id="phone-number" value="<?= $user -> userPhoneNumber ?>" placeholder="<?= ($user -> userPhoneNumber === null) ? "Non renseigné" : null ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="profile-picture">Photo de profil</label>
                <input type="text" name="profile-picture" id="profile-picture" value="<?= $user -> userProfilePicture ?>" placeholder="<?= ($user -> userProfilePicture === null) ? "Non renseignée" : null ?>" readonly>
            </div>

            <div class="form-field w33">
                <label for="birth-date">Date de naissance</label>
                <input type="text" name="birth-date" id="birth-date" value="<?= $user -> userBirthDate ?>" placeholder="<?= ($user -> userBirthDate === null) ? "Non renseignée" : null ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <div class="table-container no-footer">
                    <div class="table-header">
                        <p>Abonnements actifs</p>
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
                <input type="text" name="role" id="role" value="<?= ucfirst($user -> userRole) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <input class="bg-red" type="submit" name="submit" value="Supprimer">
            </div>
        </div>
    </form>
</div>