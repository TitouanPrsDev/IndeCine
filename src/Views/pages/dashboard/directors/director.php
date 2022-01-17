<div class="form-container hcenter w55">
    <h2><?= $director -> directorName ?></h2>

    <div class="form-group">
        <div class="form-field w15">
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="<?= $director -> directorId ?>" readonly>
        </div>

        <div class="form-field">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="<?= $director -> directorName ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="picture">Photo</label>
            <input type="text" name="picture" id="picture" value="<?= $director -> directorPicture ?>" readonly>
        </div>
    </div>
</div>