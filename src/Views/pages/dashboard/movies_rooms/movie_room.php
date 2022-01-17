<div class="form-container hcenter w55">
    <h2><?= $movieRoom -> movieRoomName ?></h2>

    <div class="form-group">
        <div class="form-field w15">
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="<?= $movieRoom -> movieRoomId ?>" readonly>
        </div>

        <div class="form-field">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="<?= $movieRoom -> movieRoomName ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <div class="form-field">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="5" placeholder="<?= ($movieRoom -> movieRoomDescription === null) ? 'Non renseignée' : null ?>" readonly><?= $movieRoom -> movieRoomDescription ?></textarea>
        </div>
    </div>
</div>