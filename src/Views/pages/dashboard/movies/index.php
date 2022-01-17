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
        <p class="title">TOTAL FILMS</p>
        <p class="value"><?= $totalMovies ?></p>
    </div>

    <div class="card">
        <p class="title">FILMS A L'AFFICHE</p>
        <p class="value"><?= $moviesScreening ?></p>
    </div>

    <div class="card">
        <p class="title">FILMS EN ATTENTE DE PROJECTION</p>
        <p class="value"><?= $moviesWaitingForScreening ?></p>
    </div>

    <div class="card">
        <p class="title">FILMS DEJA PROJETÉS</p>
        <p class="value"><?= $moviesAlreadyScreened ?></p>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <p>Films</p>

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
                    <th><input type="checkbox" onchange="checkAll(this)"></th>
                    <th>Titre</th>
                    <th>Réalisateur</th>
                    <th>Date de sortie</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Classification</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($movies as $movie): ?>
                <tr onclick="window.location.href = '/dashboard/movies/<?= $movie -> movieId ?>'">
                    <td><input type="checkbox"></td>
                    <td><?= $movie -> movieTitle ?></td>
                    <td><?= $movie -> directorName ?></td>
                    <td><?= format_date($movie -> movieReleaseDate, 'display-slash') ?></td>
                    <td><?= format_duration($movie -> movieDuration) ?></td>
                    <td>Statut</td>
                    <td><?= movie_classification($movie -> movieClassification) ?></td>
                    <td>
                        <button class="border" name="modify" value="<?= $movie -> movieId ?>">
                            <img src="/public/images/icons/regular_edit--v1.png">
                            Modifier
                        </button>
                    </td>
                    <td>
                        <button class="bg bg-red" name="delete" value="<?= $movie -> movieId ?>">
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

            <p>sur <?= $totalMovies ?></p>
        </div>

        <form class="change-page" method="post">
            <?php for ($i = 1; $i < $nbPages + 1; $i++): ?>
            <button class="border" name="page" value="<?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
        </form>
    </div>
</div>