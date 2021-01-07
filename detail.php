<?php
$marketplace = $_GET['marketplace'];


require 'vendor/autoload.php';
$client = new MongoDB\Client(
    'mongodb://user_admin:Lukman123.@cluster0-shard-00-00.1pfio.mongodb.net:27017,cluster0-shard-00-01.1pfio.mongodb.net:27017,cluster0-shard-00-02.1pfio.mongodb.net:27017/testing?ssl=true&replicaSet=atlas-12hbg6-shard-0&authSource=admin&retryWrites=true&w=majority'
);
$db = $client->testing;
$collection = $db->selectCollection('barang');
$cursor = $collection->find(
    [
        'barang_sumber' => $marketplace
    ],
    [
        'sort' => [
            'barang_harga' => 1
        ],
        'typeMap' => [
            'document' => 'array',
            'root' => 'array'
        ]
    ]
);

function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>

    <div class="container">
        <div class="title" style="margin:5%">
            <h1 class="text-center">Barang di <?= $marketplace ?></h1>
        </div>
        <?php
        //Columns must be a factor of 12 (1,2,3,4,6,12)
        $numOfCols = 2;
        $rowCount = 0;
        $bootstrapColWidth = 12 / $numOfCols;
        ?>
        <div class="row" align="center" style="margin-bottom:5%;">
            <?php
            foreach ($cursor as $barang) {
            ?>
                <div class="col-md-<?php echo $bootstrapColWidth; ?> justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="<?= $barang['barang_gambar'] ?>" alt="">
                        <div class="card-body" align="left">
                            <h5 class="card-title"><?= $barang['barang_name']; ?></h5>
                            <p class="card-text"><?php echo rupiah($barang['barang_harga']) ?></p>
                            <p class="card-text"><?= $barang['barang_sumber']; ?></p>
                            <a href="<?= $barang['barang_url'] ?>" class="btn btn-primary">Visit Store</a>
                        </div>
                    </div>
                </div>
            <?php
                $rowCount++;
                if ($rowCount % $numOfCols == 0) echo '</div><div class="row" align="center" style="margin-bottom:5%;">';
            }
            ?>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>