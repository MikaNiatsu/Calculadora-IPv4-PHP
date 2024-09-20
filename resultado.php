<?php
require("php/ipv4.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
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
            color: var(--color-text-invert);
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
            text-align: center;
            margin: 2rem 0;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #f0f0f0;
            transition: background-color 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @media (max-width: 768px) {
            th, td {
                padding: 0.75rem;
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
        <?php
        try {
            if (isset($_GET['ip']) && (isset($_GET['mask']) || isset($_GET['cidr']))) {
                echo '<div class="center-div fade-in"> <h1>Resultado</h1> </div>';
                $ip = htmlentities($_GET['ip']);
                $maskOrCidr = isset($_GET['mask']) ? htmlentities($_GET['mask']) : htmlentities($_GET['cidr']);
                $resultado = new IPv4Calculator($ip, $maskOrCidr);

                echo "<div class='table-container fade-in'>";
                echo "<table>";
                echo "<tr><th>Propiedad</th><th>Valor</th></tr>";

                foreach ($resultado->getInfo() as $key => $value) {
                    echo "<tr>";
                    echo "<td>" . $key . "</td>";
                    echo "<td>" . $value. "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
                echo '<div class="center-div fade-in"><a href="index.php" role="button">Volver</a></div>';
            } else {
                echo '<article class="pico-background-red-600 fade-in">Faltan datos</article>';
                echo '<div class="center-div fade-in"><a href="index.php" role="button">Volver</a></div>';
            }
        } catch (Exception $e) {
            echo '<article class="pico-background-red-600 fade-in">' . $e->getMessage() . '</article>';
            echo '<div class="center-div fade-in"><a href="index.php" role="button">Volver</a></div>';
        }
        ?>
    </main>

    <footer>
        <p>&copy; Miguel Linares Universidad El Bosque</p>
    </footer>
</body>
</html>