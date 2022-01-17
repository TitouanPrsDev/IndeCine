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


<div class="table-container">
    <div class="table-header">
        <p>Tarifs</p>

        <form method="post">
            <button class="bg bg-blue" name="add">
                <img src="/public/images/icons/regular_add--v1.png">
                Ajouter
            </button>
        </form>
    </div>

    <form method="post">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Nom</th>
                    <th>Tarif</th>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($pricings as $pricing): ?>
                <tr onclick="window.location.href = '/dashboard/pricings/<?= $pricing -> pricingId ?>'">
                    <td><input type="checkbox"></td>
                    <td><?= $pricing -> pricingName ?></td>
                    <td><?= $pricing -> pricing ?>€</td>
                    <td><?= $pricing -> pricingDescription ?></td>
                    <td>
                        <button class="border" name="modify" value="<?= $pricing -> pricingId ?>">
                            <img src="/public/images/icons/regular_edit--v1.png">
                            Modifier
                        </button>
                    </td>
                    <td>
                        <button class="bg bg-red" name="delete" value="<?= $pricing -> pricingId ?>">
                            <img class="img-only" src="/public/images/icons/regular_filled-trash.png">
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>

    <div class="table-footer">
        <div class="lines-number">
            <p>Afficher :</p>

            <form method="post">
                <select name="show" onchange="onSelectSubmit()">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
            </form>

            <p>sur <?= $totalPricings ?></p>
        </div>

        <form class="change-page" method="post">
            <?php for ($i = 0; $i < $nbPages + 1; $i++): ?>
            <button class="border" name="page" value="<?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
        </form>
    </div>
</div>