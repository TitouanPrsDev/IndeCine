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
        <p class="title">TOTAL SALLES</p>
        <p class="value"><?= $totalMoviesRooms ?></p>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <p>Salles</p>

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
                    <th class="w50">Nom</th>
                    <th class="w50">Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($moviesRooms as $movieRoom): ?>
                <tr onclick="window.location.href = '/dashboard/movies_rooms/<?= $movieRoom -> movieRoomId ?>'">
                    <td><input type="checkbox"></td>
                    <td><?= $movieRoom -> movieRoomName ?></td>
                    <td><?= ($movieRoom -> movieRoomDescription !== null) ? $movieRoom -> movieRoomDescription : "Non renseignée" ?></td>
                    <td>
                        <button class="border" name="modify" value="<?= $movieRoom -> movieRoomId ?>">
                            <img src="/public/images/icons/regular_edit--v1.png">
                            Modifier
                        </button>
                    </td>
                    <td>
                        <button class="bg bg-red" name="delete" value="<?= $movieRoom -> movieRoomId ?>">
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

            <p>sur <?= $totalMoviesRooms ?></p>
        </div>

        <form class="change-page" method="post">
            <?php for ($i = 1; $i < $nbPages + 1; $i++): ?>
            <button class="border" name="page" value="<?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
        </form>
    </div>
</div>