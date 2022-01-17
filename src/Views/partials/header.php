<header id="<?php if ($template == 'default') { echo 'header-default'; } else { echo 'header-admin'; } ?>">
    <div class="container">

        <div class="logo">
            <a href="/">IndéCiné</a>
        </div>

        <!-- DEFAULT TEMPLATE -->
        <?php if ($template == 'default'): ?>
        <div class="menus-center">
            <a href="/movies">Films</a>

            <!-- CONNECTED USER -->
            <?php if (isset($_SESSION['user']) && $_SESSION['user'] -> userRole === 'admin'): ?>
            <a href="/dashboard">Tableau de bord</a>
            <?php endif; ?>
        </div>

        <div class="menus-right">
            <!-- DISCONNECTED USER -->
            <?php if (!isset($_SESSION['user'])): ?>
                <button onclick="window.location.href = '/signin'">Connexion</button>
                <button class="bg bg-blue" onclick="window.location.href = '/signup'">Inscription</button>

            <!-- CONNECTED USER -->
            <?php else: ?>
                <?php if ($_SESSION['user'] -> userRole === 'client'): ?>
                <button class="border" onclick="window.location.href = '/dashboard'">
                    <?= $_SESSION['user'] -> userFirstName ?> <?= $_SESSION['user'] -> userLastName ?>
                </button>
                <?php endif; ?>
                <button class="bg bg-red" onclick="window.location.href = '/signout'">Déconnexion</button>
            <?php endif; ?>
        </div>

        <!-- DASHBOARD TEMPLATE -->
        <?php else: ?>
        <div class="menus">
            <div class="menus-right">
                <!-- DISCONNECTED USER -->
                <?php if (!isset($_SESSION['user'])): ?>
                <button onclick="window.location.href = '/signin'">Connexion</button>
                <button class="bg bg-blue" onclick="window.location.href = '/signup'">Inscription</button>

                <!-- CONNECTED USER -->
                <?php else: ?>
                    <?php if ($_SESSION['user'] -> userRole === 'client'): ?>
                    <button class="border" onclick="window.location.href = '/dashboard'">
                        <?= $_SESSION['user'] -> userFirstName ?> <?= $_SESSION['user'] -> userLastName ?>
                    </button>
                    <?php endif; ?>
                <button class="bg bg-red" onclick="window.location.href = '/signout'">Déconnexion</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</header>