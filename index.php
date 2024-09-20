<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora IPv4</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css">
    <link rel="stylesheet" href="public/style.css">
    <link rel="icon" href="public/assets/icon.ico">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        header, footer {
            background-color: whitesmoke;
            color: white;
            padding: 1rem;
            text-align: center;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: 0.25rem solid #2a3345;
            transition: border-color 0.2s ease-out;
        }

        @media (prefers-color-scheme: dark) {
            header, footer {
                background-color: #2a3345;
                
            }
        }

        header:hover, footer:hover {
            border-color: var(--color-accent-hover);
        }

        footer {
            margin-top: auto;
        }

        .center-div {
            margin: 2rem 0;
        }

        form {
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        form:hover {
            transform: scale(1.02);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .hidden {
            display: none;
        }

        .pico-background-red-600 {
            background: #ff4d4d;
            color: white;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <ul>
                <strong>Calculadora IPv4</strong>
            </ul>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="explicacion.html">Funcionamiento</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="center-div">
            <?php
            session_start();
            if (isset($_GET['submit'])) {
                if (!isset($_GET['check'])) {
                    if (isset($_GET['ip1']) && isset($_GET['ip2']) && isset($_GET['ip3']) && isset($_GET['ip4']) && isset($_GET['mask1']) && isset($_GET['mask2']) && isset($_GET['mask3']) && isset($_GET['mask4'])) {
                        if (!is_numeric($_GET['ip1']) || !is_numeric($_GET['ip2']) || !is_numeric($_GET['ip3']) || !is_numeric($_GET['ip4']) || !is_numeric($_GET['mask1']) || !is_numeric($_GET['mask2']) || !is_numeric($_GET['mask3']) || !is_numeric($_GET['mask4'])) {
                            $_SESSION['error'] = 'Los valores no pueden ser letras o estar vacios';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&mask1={$_GET['mask1']}&mask2={$_GET['mask2']}&mask3={$_GET['mask3']}&mask4={$_GET['mask4']}");
                            return;
                        }
                        if ($_GET['ip1'] > 255 || $_GET['ip2'] > 255 || $_GET['ip3'] > 255 || $_GET['ip4'] > 255 || $_GET['mask1'] > 255 || $_GET['mask2'] > 255 || $_GET['mask3'] > 255 || $_GET['mask4'] > 255) {
                            $_SESSION['error'] = 'Los valores no pueden ser mayor a 255';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&mask1={$_GET['mask1']}&mask2={$_GET['mask2']}&mask3={$_GET['mask3']}&mask4={$_GET['mask4']}");
                            return;
                        }
                        if ($_GET['ip1'] < 0 || $_GET['ip2'] < 0 || $_GET['ip3'] < 0 || $_GET['ip4'] < 0 || $_GET['mask1'] < 0 || $_GET['mask2'] < 0 || $_GET['mask3'] < 0 || $_GET['mask4'] < 0) {
                            $_SESSION['error'] = 'Los valores no pueden ser negativos';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&mask1={$_GET['mask1']}&mask2={$_GET['mask2']}&mask3={$_GET['mask3']}&mask4={$_GET['mask4']}");
                            return;
                        }
                        header("Location: resultado.php?ip=" . $_GET['ip1'] . "." . $_GET['ip2'] . "." . $_GET['ip3'] . "." . $_GET['ip4'] . "&mask=" . $_GET['mask1'] . "." . $_GET['mask2'] . "." . $_GET['mask3'] . "." . $_GET['mask4']);
                        return;
                    } else
                        echo '<article class="pico-background-red-600">Faltan datos</article>';
                } else {
                    if (isset($_GET['ip1']) && isset($_GET['ip2']) && isset($_GET['ip3']) && isset($_GET['ip4']) && isset($_GET['cidr'])) {
                        if (!is_numeric($_GET['ip1']) || !is_numeric($_GET['ip2']) || !is_numeric($_GET['ip3']) || !is_numeric($_GET['ip4']) || !is_numeric($_GET['cidr'])) {
                            $_SESSION['error'] = 'Los valores no pueden ser letras o estar vacios';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&cidr={$_GET['cidr']}");
                            return;
                        }
                        if ($_GET['ip1'] > 255 || $_GET['ip2'] > 255 || $_GET['ip3'] > 255 || $_GET['ip4'] > 255) {
                            $_SESSION['error'] = 'Los valores no pueden ser mayor a 255';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&cidr={$_GET['cidr']}");
                            return;
                        }
                        if ($_GET['ip1'] < 0 || $_GET['ip2'] < 0 || $_GET['ip3'] < 0 || $_GET['ip4'] < 0) {
                            $_SESSION['error'] = 'Los valores no pueden ser negativos';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&cidr={$_GET['cidr']}");
                            return;
                        }
                        if ($_GET['cidr'] < 0 || $_GET['cidr'] > 30) {
                            $_SESSION['error'] = 'La máscara no puede ser negativa ni mayor a 30';
                            header("Location: index.php?ip1={$_GET['ip1']}&ip2={$_GET['ip2']}&ip3={$_GET['ip3']}&ip4={$_GET['ip4']}&cidr={$_GET['cidr']}");
                            return;
                        }
                        header("Location: resultado.php?ip={$_GET['ip1']}.{$_GET['ip2']}.{$_GET['ip3']}.{$_GET['ip4']}&cidr={$_GET['cidr']}");
                        return;
                    } else
                        echo '<article class="pico-background-red-600">Faltan datos</article>';
                }
            }
            ?>
        </div>

        <form method="get">
            <label for="ip">IP:</label>
            <div class="grid" id="ip">
                <input type="number" name="ip1" id="ip1" placeholder="0-255" value="<?php if (isset($_GET['ip1'])) echo $_GET['ip1']; ?>">
                <input type="number" name="ip2" id="ip2" placeholder="0-255" value="<?php if (isset($_GET['ip2'])) echo $_GET['ip2']; ?>">
                <input type="number" name="ip3" id="ip3" placeholder="0-255" value="<?php if (isset($_GET['ip3'])) echo $_GET['ip3']; ?>">
                <input type="number" name="ip4" id="ip4" placeholder="0-255" value="<?php if (isset($_GET['ip4'])) echo $_GET['ip4']; ?>">
            </div>
            <label for="mask">Máscara:</label>
            <div class="grid" id="mask">
                <input type="number" name="mask1" id="mask1" placeholder="0-255" value="<?php if (isset($_GET['mask1'])) echo $_GET['mask1']; ?>">
                <input type="number" name="mask2" id="mask2" placeholder="0-255" value="<?php if (isset($_GET['mask2'])) echo $_GET['mask2']; ?>">
                <input type="number" name="mask3" id="mask3" placeholder="0-255" value="<?php if (isset($_GET['mask3'])) echo $_GET['mask3']; ?>">
                <input type="number" name="mask4" id="mask4" placeholder="0-255" value="<?php if (isset($_GET['mask4'])) echo $_GET['mask4']; ?>">
                <input type="number" class="hidden" name="cidr" id="cidr" placeholder="0-30" value="<?php if (isset($_GET['cidr'])) echo $_GET['cidr']; ?>">
                <label>
                    <input type="checkbox" name="check" id="cidr-check" <?php if (isset($_GET['cidr'])) echo 'checked'; ?>>
                    Notacion CIDR
                </label>
            </div>
            <input type="submit" name="submit" value="Calcular">
        </form>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<article class="pico-background-red-600">' . $_SESSION['error'] . '</article>';
            unset($_SESSION['error']);
        }
        ?>
    </main>

    <footer>
        <p>&copy; Miguel Linares Univeridad El Bosque</p>
    </footer>

    <script src="public/main.js"></script>
</body>

</html>