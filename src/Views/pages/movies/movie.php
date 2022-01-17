<h1><?= $movie -> movieTitle ?></h1>

<div class="container movie-container">
    <div class="general-infos-container">
        <div class="poster-container">
            <h2>Affiche</h2>
            <img src="<?= $movie -> moviePoster ?>" alt="">
        </div>

        <div class="synopsis-container">
            <h2>Synopsis</h2>
            <p><?= $movie -> movieSynopsis ?></p>
        </div>
    </div>

    <div class="right-container">
        <div class="informations-container">
            <h2>Informations</h2>

            <div class="infos">
                <p><span class="bold">Date de sortie : </span><?= format_date($movie -> movieReleaseDate, 'display-slash') ?></p>
                <p><span class="bold">Réalisateur : </span><?= $movie -> directorName ?></p>
                <p><span class="bold">Durée : </span><?= format_duration($movie -> movieDuration) ?></p>
                <p><span class="bold">Classification : </span><?= movie_classification($movie -> movieClassification) ?></p>
            </div>
        </div>

        <div class="screenings-container">
            <h2>Séances</h2>

            <?php if (!empty($screeningsList)): ?>
            <div class="table-screenings">

                <form method="post">
                    <div class="dates">
                        <?php for ($i = 0; $i < count($dates); $i++): ?>
                            <div class="date date-<?= $i + 1 ?>" onclick="switchTab(this)">
                                <p><?= substr($dates[$i], 3, 2) . "/" . substr($dates[$i], 0, 2) ?></p>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="screenings">
                        <?php for ($i = 0; $i < count($dates); $i++): ?>
                            <div class="date date-<?= $i + 1 ?>">
                                <?php foreach ($screeningsList[$dates[$i]] as $screening): ?>
                                    <button class="no-style screening" name="screening" value="<?= $screening -> screeningId ?>">
                                        <p><?= $screening -> screeningTime ?></p>
                                        <p>Salle <?= $screening -> movieRoom_id ?></p>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </form>
            </div>

            <?php else: ?>
                <p>Aucune séance disponible</p>
            <?php endif; ?>
        </div>
    </div>
</div>