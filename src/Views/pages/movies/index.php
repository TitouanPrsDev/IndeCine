<h1>Films</h1>

<div class="cards-container card-reserve">
    <?php foreach($movies as $movie): ?>
    <div class="card">
        <h2><?= $movie -> movieTitle ?></h2>

        <div class="infos">
            <p><span class="bold">Date de sortie : </span><?= format_date($movie -> movieReleaseDate, 'display-slash') ?></p>
            <p><span class="bold">Réalisateur : </span><?= $movie -> directorName ?></p>
            <p><span class="bold">Durée : </span><?= format_duration($movie -> movieDuration) ?></p>
        </div>

        <form method="post">
            <button class="bg bg-blue" name="book" value="<?= $movie -> movieId ?>">Réserver</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>