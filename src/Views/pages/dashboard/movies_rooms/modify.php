<div class="form-container hcenter w55">
    <h2><?= $movieRoom -> movieRoomName ?></h2>

    <form method="post">
        <div class="form-group">
            <div class="form-field w15">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" value="<?= $movieRoom -> movieRoomId ?>" readonly>
            </div>

            <div class="form-field">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" value="<?= $movieRoom -> movieRoomName ?>">

                <p class="field-error"><?= (isset($fieldsErrors['name'])) ? $fieldsErrors['name'] : null ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" placeholder="<?= ($movieRoom -> movieRoomDescription === null) ? 'Non renseignée' : null ?>"><?= $movieRoom -> movieRoomDescription ?></textarea>

                <p class="field-error"><?= (isset($fieldsErrors['description'])) ? $fieldsErrors['description'] : null ?></p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-field">
                <input class="bg-blue" type="submit" name="submit" value="Modifier">
            </div>
        </div>
    </form>
</div>