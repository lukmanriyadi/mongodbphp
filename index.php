<?php

require 'vendor/autoload.php';
require 'autoload.php';
$db_user = env('DB_USERNAME');
$db_pass = env('DB_PASSWORD');
$client = new MongoDB\Client(
    'mongodb://' . $db_user . ':' . $db_pass . '@cluster0-shard-00-00.1pfio.mongodb.net:27017,cluster0-shard-00-01.1pfio.mongodb.net:27017,cluster0-shard-00-02.1pfio.mongodb.net:27017/testing?ssl=true&replicaSet=atlas-12hbg6-shard-0&authSource=admin&retryWrites=true&w=majority'
);
$db = $client->testing;
$collection = $db->selectCollection('barang');

$cursor = $collection->find(
    [],
    [
        'limit' => 3,
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

$cursor2 = $collection->aggregate(
    array(
        array(
            '$group' => array(
                '_id' => '$barang_sumber',
                'maksHarga' => array('$max' => '$barang_harga'),
                'minHarga' => array('$min' => '$barang_harga'),
                'avgHarga' => array('$avg' => '$barang_harga'),
            ),
        )
    ),
    array(
        'typeMap' => [
            'document' => 'array',
            'root' => 'array'
        ]
    )
);

$countTokopedia = $collection->countDocuments(array('barang_sumber' => 'tokopedia'));
$countBukalapak = $collection->countDocuments(array('barang_sumber' => 'bukalapak'));
$countShopee = $collection->countDocuments(array('barang_sumber' => 'shopee'));
$countJdid = $collection->countDocuments(array('barang_sumber' => 'jd.id'));
$countBlibli = $collection->countDocuments(array('barang_sumber' => 'blibli'));

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Perbandingan Harga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="title" style="margin-top:5%">
            <h1 class="text-center">Perbandingan Harga</h1>
        </div>
        <div class="nama-barang" style="margin:10%">
            <h3 class="text-center">Apple iPhone 12 128Gb</h3>
        </div>
        <div class="kategori-info" style="margin-top:10%;margin-bottom:5%">
            <h4 class="text-center">3 Barang Termurah</h4>
        </div>
        <div class="row">

            <?php
            foreach ($cursor as $barang) {
            ?>
                <div class="col-md-4" align="center">
                    <div class="justify-content-center">
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="<?= $barang['barang_gambar'] ?>" alt="Card image cap">
                            <div class="card-body" align="left">
                                <h5 class="card-title"><?= $barang['barang_name']; ?></h5>
                                <p class="card-text"><?php echo rupiah($barang['barang_harga']) ?></p>
                                <p class="card-text"><?= $barang['barang_sumber']; ?></p>
                                <a href="<?= $barang['barang_url'] ?>" class="btn btn-primary">Visit Store</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="kategori-info" style="margin-top:10%;margin-bottom:5%">
            <h4 class="text-center">Harga Per Marketplace </h4>
        </div>
        <div class="row justify-content-center">

            <?php
            foreach ($cursor2 as $harga) {
            ?>
                <div class="col-md-2" align="center">
                    <div class="card" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title"><b><?= $harga['_id']; ?></b></h5>
                            <h6 class="card-title">Termurah</h6>
                            <p class="card-text">
                                <?php
                                echo rupiah($harga['minHarga']);
                                ?>
                            </p>
                            <h6 class="card-title">Termahal</h6>
                            <p class="card-text">
                                <?php
                                echo rupiah($harga['maksHarga']);
                                ?>
                            </p>
                            <h6 class="card-title">Rata-Rata</h6>
                            <p class="card-text">
                                <?php
                                echo rupiah($harga['avgHarga']);
                                ?>
                            </p>
                            <a href="detail.php?marketplace=<?= $harga['_id'] ?>" class="btn btn-primary">Check Barang</a>

                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
        <div class="kategori-info" style="margin-top:10%;margin-bottom:5%">
            <h4 class="text-center">Jumlah Data Per Marketplace </h4>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-2" align="center">
                <div class="card" style="width: 12rem;">
                    <div class="card-header">
                        Tokopedia
                    </div>
                    <div class="card-body">

                        <h2><?= $countTokopedia; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2" align="center">
                <div class="card" style="width: 12rem;">
                    <div class="card-header">
                        JD.ID
                    </div>
                    <div class="card-body">

                        <h2><?= $countJdid; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2" align="center">
                <div class="card" style="width: 12rem;">
                    <div class="card-header">
                        Shopee
                    </div>
                    <div class="card-body">

                        <h2><?= $countShopee; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2" align="center">
                <div class="card" style="width: 12rem;">
                    <div class="card-header">
                        Bukalapak
                    </div>
                    <div class="card-body">

                        <h2><?= $countBukalapak; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2" align="center">
                <div class="card" style="width: 12rem;">
                    <div class="card-header">
                        Blibli
                    </div>
                    <div class="card-body">

                        <h2><?= $countBlibli; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center" style="margin-top:10%;">
            <div class="col-md-10">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        <?= $countTokopedia; ?>,
                        <?= $countShopee; ?>,
                        <?= $countBukalapak; ?>,
                        <?= $countBukalapak; ?>,
                        <?= $countJdid; ?>,
                    ],
                    backgroundColor: [
                        'rgba(0, 255, 64, 1)',
                        'rgba(255, 91, 36, 1)',
                        'rgba(255, 36, 61, 1)',
                        'rgba(46, 39, 236, 1)',
                        'rgba(244, 31, 31, 1)',
                    ],
                    label: 'Jumlah Data'
                }],
                labels: [
                    'Tokopedia',
                    'Shopee',
                    'Bukalapak',
                    'Blibli',
                    'JD.ID'
                ]
            },
            options: {
                responsive: true
            }
        });
    </script>


</body>

</html>