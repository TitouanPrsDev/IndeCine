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


<div class="cards-container">
    <div class="card">
        <p class="title">TOTAL SEANCES</p>
        <p class="value"><?= $totalScreenings ?></p>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <p>Séances</p>

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
                    <th>ID</th>
                    <th>Film</th>
                    <th>Salle</th>
                    <th>Date</th>
                    <th>Horaire</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($screenings as $screening): ?>
                <tr onclick="window.location.href = '/dashboard/screenings/<?= $screening -> screeningId ?>'">
                    <th><input type="checkbox"></th>
                    <td><?= $screening -> screeningId ?></td>
                    <td><?= $screening -> movieTitle ?></td>
                    <td><?= $screening -> movieRoomName ?></td>
                    <td><?= format_date($screening -> screeningDate, 'display-slash') ?></td>
                    <td><?= $screening -> screeningTime ?></td>
                    <td>
                        <button class="border" name="modify" value="<?= $screening -> screeningId ?>">
                            <img src="/public/images/icons/regular_edit--v1.png">
                            Modifier
                        </button>
                    </td>
                    <td>
                        <button class="bg bg-red" name="delete" value="<?= $screening -> screeningId ?>">
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

            <p>sur <?= $totalScreenings ?></p>
        </div>

        <form class="change-page" method="post">
            <?php for ($i = 1; $i < $nbPages + 1; $i++): ?>
            <button class="border" name="page" value="<?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
        </form>
    </div>
</div>